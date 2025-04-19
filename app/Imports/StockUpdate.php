<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Stock;
use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockUpdate implements ToModel, WithHeadingRow
{
    // public function model(array $row)
    // {
    //     $itemCode = trim($row['item_code']);
    //     $expiryDateInput = trim($row['expiry_date']);
    //     $availableQuantity = (float) $row['available_quantity'];

    //     // Validate expiry date
    //     $expiryDate = null;
    //     if (!empty($expiryDateInput)) {
    //         $timestamp = strtotime($expiryDateInput);
    //         if ($timestamp !== false) {
    //             $expiryDate = date('Y-m-d', $timestamp);
    //         }
    //     }

    //     $item = Item::where('item_code', $itemCode)->first();

    //     if ($item) {
    //         $itemId = $item->id;

    //         // Check if expiry date is valid before querying
    //         $stockQuery = Stock::where('item_id', $itemId);
    //         if ($expiryDate !== null) {
    //             $stockQuery->where('expiry_date', $expiryDate);
    //         } else {
    //             $stockQuery->whereNull('expiry_date');
    //         }

    //         $stock = $stockQuery->first();

    //         // Update or create batch logic
    //         if ($stock) {
    //             $stock->batch_no = $row['batch'];
    //             $stock->save();
    //         } else {
    //             $stock = Stock::create([
    //                 'item_id'      => $itemId,
    //                 'expiry_date'  => $expiryDate,  // null if invalid
    //                 'item_price'   => $row['sale_price'],
    //                 'batch_no'     => $row['batch'],
    //                 'created_at'   => now(),
    //                 'updated_at'   => now()
    //             ]);
    //         }

    //         // Calculate current stock from stock transactions
    //         $currentStock = (float) Stock::getTotalStockByItem($itemId);
    //         $diff = $availableQuantity - $currentStock;

    //         if (abs($diff) > 0) {
    //             $status = $diff > 0
    //                 ? StockTransaction::STATUS_INCOMING_STOCK
    //                 : StockTransaction::STATUS_OUTGOING_STOCK;

    //             StockTransaction::create([
    //                 'item_id'          => $itemId,
    //                 'stock_id'         => $stock->id,
    //                 'quantity'         => abs($diff),
    //                 'status'           => $status,
    //                 'transaction_date' => now()
    //             ]);
    //         }
    //     }

    //     return null;
    // }
    public function model(array $row)
    {
        $itemCode = trim($row['item_id']);
        $expiryDateInput = trim($row['expiry_date']);
        $availableQuantity = (float) $row['available_quantity'];

        // Validate expiry date
        $expiryDate = null;
        if (!empty($expiryDateInput)) {
            $timestamp = strtotime($expiryDateInput);
            if ($timestamp !== false) {
                $expiryDate = date('Y-m-d', timestamp: $timestamp);
            }
        }





        // Update or create batch logic

        $stock = Stock::create([
            'item_id'      => $itemCode,
            'expiry_date'  => $expiryDate,  // null if invalid
            'item_price'   => $row['item_price'],
            'created_at'   => now(),
            'updated_at'   => now()
        ]);



        if ($stock) {
            return $stock;
        }

        return null;
    }
}
