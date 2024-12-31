<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceDetailImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::transaction(function () use ($row) {
            // 1. Retrieve Invoice
            $invoice = Invoice::where('old_invoice_id', $row['old_invoice_id'])->first();
            if (!$invoice) {
                return null;
            }

            $patientId = $invoice->paitent_id ?? 0;

            // 2. Retrieve/Create Item
            $item = Item::firstOrCreate(
                ['item_code' => $row['item_code']],
                ['item_type' => $row['item_type'], 'name' => $row['item_name']]
            );

            // 3. Ensure Stock Exists or Update Stock Quantity
            $stock = Stock::where('item_id', $item->id)
                ->where('expiry_date', $row['expiry_date'] ?? null)
                ->first();

            if ($stock) {
                // Update existing stock quantity
                $stock->order_quantity += $row['quantitiy'];
                $stock->save();
            } else {
                // Create new stock entry
                $stock = Stock::create([
                    'item_id' => $item->id,
                    'order_quantity' => $row['quantitiy'],
                    'item_price' => $row['mrp'], // Optional
                    'total_price' => $row['mrp'] * $row['quantitiy'], // Optional
                    'status' => 1, // Default to 'In Stock'
                    'expiry_date' => $row['expiry_date'] ?? null,
                    'batch_no' => $row['batch'] ?? "",
                ]);
            }

            // 4. Create Invoice Detail
            $invoiceDetail = InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'old_invoice_id' => $row['old_invoice_id'],
                'old_invoice_detail_id' => $row['old_invoice_detail_id'],
                'paitent_id' => $patientId,
                'item_id' => $item->id,
                'stock_id' => $stock->id, // Attach the stock id
                'item_type' => $row['item_type'],
                'item_price' => $row['mrp'],
                'discount_amount' => $row['discount_amount'] ?? 0,
                'add_dis_amount' => $row['add_dis_amount'] ?? 0,
                'add_dis_percent' => $row['add_dis_percent'] ?? 0,
                'total_amount' => $row['amount'],
                'created_at' => $row['created_at'] ?? now(),
            ]);

            // 5. Create Stock Transaction
            StockTransaction::create([
                'stock_id' => $stock->id,
                'item_id' => $item->id,
                'invoice_id' => $invoiceDetail->id,
                'quantity' => $row['quantitiy'],
                'item_price' => $row['mrp'],
                'total_price' => $row['quantitiy'] * $row['mrp'],
                'status' => 2, // Outgoing transaction
                'transaction_date' => $row['created_at'] ?? now(),
            ]);
        });
    }
}
