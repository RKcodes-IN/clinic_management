<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ItemsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::beginTransaction(); // Start transaction for consistency

        try {

            $expiryDate = null;
            if (!empty($row['expiry_date'])) {
                try {
                    // Try to parse the date, assuming it follows 'd/m/Y'
                    $expiryDate = Carbon::createFromFormat('d/m/Y', $row['expiry_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Log or handle invalid date formats
                    $expiryDate = null;  // Set to null or handle accordingly
                }
            }
            // Handle Company
            $company = SourceCompany::firstOrCreate(
                ['name' => $row['company_name']],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Handle Brand
            $brand = Brand::firstOrCreate(
                ['name' => $row['brand']],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Handle Category
            $category = Category::firstOrCreate(
                ['name' => $row['category']],
                ['created_at' => now(), 'updated_at' => now()]
            );


            // Create or find the Item
            $item = Item::firstOrCreate(
                ['item_code' => $row['item_code']],
                [
                    'item_type' => 1,
                    'uom_type' => 0,
                    'name' => $row['name'],
                    'source_company' => $company->id,
                    'brand' => $brand->id,
                    'category' => $category->id,
                    'ideal_quantity' => $row['ideal_stock_alerts'],
                    'status' => 1
                ]
            );

            // Handle Stock based on expiry date
            $stock = Stock::create([
                'purchase_order_id' => 0,
                'purchase_order_item_id' => 0,
                'item_id' => $item->id,
                'order_quantity' => $row['quantity'],
                'item_price' => $row['item_price'],
                'total_price' => $row['quantity'] * $row['item_price'],
                'order_date' => null,
                'received_date' => $row['receive_date'],
                'expiry_date' => $expiryDate, // Assume expiry_date is in Excel
                'batch_no' => $row['batch_no'], // Assume expiry_date is in Excel
                'status' => 1, // In Stock
                'created_by' => auth()->id() ?? 1, // Or default user ID
                'updated_by' => auth()->id() ?? 1
            ]);

            // Create a Stock Transaction
            StockTransaction::create([
                'stock_id' => $stock->id,
                'item_id' => $item->id,
                'purchase_order_id' => 0,
                'quantity' => $row['quantity'],
                'item_price' => $row['item_price'],
                'total_price' => $row['item_price'],
                'status' => 1, // Transaction status (e.g., incoming stock)
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1
            ]);

            DB::commit(); // Commit transaction

            return $item; // Return the item for consistency
        } catch (\Exception $e) {
            DB::rollback(); // Rollback on failure
            throw $e; // Handle error gracefully
        }
    }
}
