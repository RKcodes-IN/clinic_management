<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\UomType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;



class ItemsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            // Parse and validate receive_date
            $receiveDate = $this->parseDate($row['receive_date'] ?? null, 'd-m-Y', 'Y-m-d H:i:s');


            // Parse expiry_date
            $expiryDate = $this->parseDate($row['expiry_date'] ?? null, 'd-m-Y', 'Y-m-d H:i:s');

            // Handle Company
            $company = SourceCompany::firstOrCreate(
                ['name' => $row['company_name']],
                ['created_at' => $receiveDate, 'updated_at' => now()]
            );

            // Handle Brand
            $brand = Brand::firstOrCreate(
                ['name' => $row['brand']],
                ['created_at' => $receiveDate, 'updated_at' => now()]
            );

            // Handle Category
            $category = Category::firstOrCreate(
                ['name' => $row['category']],
                ['created_at' => $receiveDate, 'updated_at' => now()]
            );

            // Handle UOM
            $uom = UomType::firstOrCreate(
                ['name' => $row['uom'] ?? "na"],
                ['created_at' => $receiveDate, 'updated_at' => now()]
            );

            // Create or find the Item
            $item = Item::firstOrCreate(
                ['item_code' => $row['item_code']],
                [
                    'item_type' => 1,
                    'uom_type' => $uom->id,
                    'name' => $row['name'],
                    'source_company' => $company->id,
                    'brand' => $brand->id,
                    'category' => $category->id,
                    'ideal_quantity' => $row['ideal_stock_alerts'],
                    'created_at' => $receiveDate,
                    'status' => 1
                ]
            );

            // Handle Stock
            $stock = new Stock([
                'purchase_order_id' => 0,
                'purchase_order_item_id' => 0,
                'item_id' => $item->id,
                'order_quantity' => $row['quantity'],
                'item_price' => $row['item_price'],
                'total_price' => $row['quantity'] * $row['item_price'],
                'order_date' => null,
                'received_date' => $receiveDate,
                'expiry_date' => $expiryDate,
                'batch_no' => $row['batch_no'],
                'status' => 1,
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1
            ]);

            // Disable automatic timestamps and set created_at
            $stock->timestamps = false;
            $stock->created_at = $receiveDate;
            $stock->save();

            // Handle Stock Transaction
            $stockTransaction = new StockTransaction([
                'stock_id' => $stock->id,
                'item_id' => $item->id,
                'purchase_order_id' => 0,
                'quantity' => $row['quantity'],
                'item_price' => $row['item_price'],
                'total_price' => $row['quantity'] * $row['item_price'],
                'transaction_date' => $receiveDate,
                'status' => 1,
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1
            ]);

            // Disable automatic timestamps and set created_at
            $stockTransaction->timestamps = false;
            $stockTransaction->created_at = $receiveDate;
            $stockTransaction->save();

            DB::commit();

            return $item;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Parse a date string with fallback for invalid formats.
     *
     * @param string|null $date
     * @param string $inputFormat
     * @param string $outputFormat
     * @return string|null
     */
    private function parseDate($date, $inputFormat = 'd-m-Y', $outputFormat = 'Y-m-d H:i:s')
    {
        if (empty($date)) {
            return null;
        }

        try {
            return Carbon::createFromFormat($inputFormat, $date)->format($outputFormat);
        } catch (\Exception $e) {
            \Log::warning('Invalid date format', ['date' => $date, 'expected_format' => $inputFormat]);
            return null;
        }
    }
}
