<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseOrderItemsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */  public function model(array $row)
    {
        // Fetch the item based on item_code
        $item = Item::where('item_code', $row['item_code'])->first();

        // Handle if item is not found
        if (!$item) {
            return null; // Skip this row or handle accordingly
        }

        // Create Purchase Order Item
        $purchaseOrderItem = PurchaseOrderItem::create([
            'purchase_order_id' => $row['purchase_order_id'], // Assuming default or dynamic value
            'source_company_id' => 0, // Assuming a default company ID or get dynamically
            'item_id' => $item->id,
            'uom_type_id' => 0, // Default or dynamic UOM
            'quantity' => $row['quantity'],
            'item_price' => $item->price ?? 0, // Assuming item has a price field
            'total_price' => $row['quantity'] * ($item->price ?? 0),
            'order_date' => $row['created_at'] ? Carbon::parse($row['created_at']) : now(),
            'recieved_date' => null,
            'status' => 1
        ]);

        // Create Stock Entry
        $stock = Stock::create([
            'purchase_order_id' => $purchaseOrderItem->purchase_order_id,
            'purchase_order_item_id' => $row['purchase_order_id'],
            'item_id' => $item->id,
            'order_quantity' => $row['quantity'],
            'item_price' => 0,
            'total_price' => 0,
            'order_date' => now(),
            'received_date' => null,
            'expiry_date' => $row['created_at'], // Expiry date set to null
            'status' => 1, // Assuming status 1 for "In Stock"
            'created_by' => auth()->id() ?? 1
        ]);

        // Create Stock Transaction
        StockTransaction::create([
            'stock_id' => $stock->id,
            'item_id' => $item->id,
            'invoice_id' => null,
            'purchase_order_id' =>$row['purchase_order_id'],
            'quantity' => $row['quantity'],
            'item_price' => 0,
            'total_price' => 0,
            'status' => 1, // Status for "Added"
            'created_by' => auth()->id() ?? 1
        ]);

        return $purchaseOrderItem;
    }
}
