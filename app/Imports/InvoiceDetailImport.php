<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\PatientDetail;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceDetailImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        DB::transaction(function () use ($row) {
            // 1. Retrieve invoice_id
            $invoice = Invoice::where('old_invoice_id', $row['old_invoice_id'])->first();
            if (!$invoice) {
            }


            $patienId = $invoice->paitent_id ?? 0;

            // 3. Retrieve/Create item_id
            if (!empty($row['item_code'])) {
                $item = Item::firstOrCreate(
                    ['item_code' => $row['item_code']],
                    ['item_type' => $row['item_type'], 'item_name' => $row['item_name']] // Adjust as needed
                );

                if (in_array($row['item_type'], [2, 3])) {
                    // Check if stock with same item_id and expiry_date already exists
                    $existingStock = Stock::where('item_id', $item->id)
                        ->where('expiry_date', $row['expiry_date'])
                        ->first();

                    if (!$existingStock) {
                        // Create stock entry for item_type 2 or 3
                        Stock::create([
                            'item_id' => $item->id,
                            'order_quantity' => 0, // Adjust as needed
                            'total_price' => $row["mrp"], // Adjust as needed
                            'status' => 1, // Default to 'In Stock'
                            'expiry_date' => $row['expiry_date'], // Ensure expiry_date is included
                        ]);
                    }
                }

                // 4. Create invoice detail
                $invoiceDetail = InvoiceDetail::create([
                    'invoice_id' => $invoice->id ?? 0,
                    'old_invoice_id' => $row['old_invoice_id'],
                    'old_invoice_detail_id' => $row['old_invoice_detail_id'],
                    'paitent_id' => $patienId ?? 0,
                    'item_id' => $item->id,
                    'item_type' => $row['item_type'],
                    'item_price' => $row['mrp'],
                    'discount_amount' => $row['discount_amount'],
                    'add_dis_amount' => $row['add_dis_amount'],
                    'add_dis_percent' => $row['add_dis_percent'],
                    'total_amount' => $row['amount'],
                    'created_at' => $row["created_at"], // or parse $row['created_at']
                ]);

                // 5. Manage stock and stock transactions
                if ($row['item_type'] == 1) {
                    $remainingQuantity = $row['quantitiy'];

                    // Fetch stocks ordered by expiry date, including those without an expiry date
                    $stocks = Stock::where('item_id', $item->id)
                        ->orderByRaw('CASE
                        WHEN expiry_date IS NULL OR expiry_date = 0 THEN 1
                        ELSE 0
                    END, expiry_date ASC') // Handle stocks with NULL expiry dates first
                        ->get();

                    foreach ($stocks as $stock) {
                        if ($remainingQuantity <= 0) {
                            break;
                        }

                        $deductQuantity = min($remainingQuantity, $stock->order_quantity);

                        // Create outgoing stock transaction
                        StockTransaction::create([
                            'stock_id' => $stock->id,
                            'item_id' => $item->id,
                            'invoice_id' => $invoiceDetail->id,
                            'quantity' => $remainingQuantity,
                            'item_price' => $stock->item_price,
                            'total_price' => $deductQuantity * $stock->item_price,
                            'status' => 2, // Outgoing stock
                            'transaction_date' => $row["created_at"],
                            'created_at' => $row["created_at"],
                        ]);

                        $remainingQuantity -= $deductQuantity;
                    }

                    // If all stocks are depleted (remainingQuantity > 0), create transactions with any stock ID
                    // if ($remainingQuantity > 0) {
                    //     // $fallbackStock = Stock::where('item_id', $item->id)->first(); // Pick any available stock

                    //     // Create outgoing stock transaction with the remaining quantity
                    //     StockTransaction::create([
                    //         'stock_id' => $stock->id,
                    //         'item_id' => $item->id,
                    //         'invoice_id' => $invoiceDetail->id,
                    //         'quantity' => $remainingQuantity,
                    //         'item_price' => $stock->item_price,
                    //         'total_price' => $remainingQuantity * $stock->item_price,
                    //         'status' => 2, // Outgoing stock
                    //         'transaction_date' => $row["created_at"],

                    //         'created_at' => $row["created_at"],
                    //     ]);
                    // }
                }
            }
        });
    }
}
