<?php

use App\Models\Item;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseOrderItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Fetch the item based on item_code
        $item = Item::where('item_code', $row['item_code'])->first();

        // Skip if item is not found
        if (!$item) {
            return null;
        }

        $orderDate = !empty($row['created_at']) ? Carbon::parse($row['created_at'])->format('Y-m-d') : now();

        // Create Purchase Order Item
        $purchaseOrderItem = PurchaseOrderItem::create([
            'purchase_order_id' => $row['purchase_order_id'] ?? null,
            'source_company_id' => 0, // Default or dynamic source company ID
            'item_id' => $item->id,
            'uom_type_id' => 0, // Default or dynamic UOM type
            'quantity' => $row['quantity'] ?? 0,
            'item_price' => $item->price ?? 0,
            'total_price' => ($row['quantity'] ?? 0) * ($item->price ?? 0),
            'order_date' => $orderDate,
            'recieved_date' => null,
            'status' => 1
        ]);

        // Create Stock Entry
        $stock = Stock::create([
            'purchase_order_id' => $purchaseOrderItem->purchase_order_id,
            'purchase_order_item_id' => $purchaseOrderItem->id,
            'item_id' => $item->id,
            'order_quantity' => $row['quantity'] ?? 0,
            'item_price' => $item->price ?? 0,
            'total_price' => ($row['quantity'] ?? 0) * ($item->price ?? 0),
            'order_date' => $orderDate,
            'received_date' => null,
            'expiry_date' => $row['expiry_date'] ? Carbon::parse($row['expiry_date'])->format('Y-m-d') : null,
            'status' => 1,
            'created_by' => auth()->id() ?? 1
        ]);

        // Create Stock Transaction
        StockTransaction::create([
            'stock_id' => $stock->id,
            'item_id' => $item->id,
            'invoice_id' => null,
            'purchase_order_id' => $row['purchase_order_id'] ?? null,
            'quantity' => $row['quantity'] ?? 0,
            'item_price' => $item->price ?? 0,
            'total_price' => ($row['quantity'] ?? 0) * ($item->price ?? 0),
            'status' => 1,
            'created_by' => auth()->id() ?? 1
        ]);

        return $purchaseOrderItem;
    }
}
