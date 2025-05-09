<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'purchase_order_item_id',
        'item_id',
        'order_quantity',
        'item_price',
        'total_price',
        'order_date',
        'received_date',
        'batch_no',
        'expiry_date',
        'status',
        'created_by',
        'updated_by',
        'correct_exp',
        'correct_price',
        'correct_stock',
        'delete_yn'
    ];

    const IN_STOCK = 1;
    const OUT_OF_STOCK = 2;
    const EXPIRED = 3;

    public static function getTotalStock(int $stockId): int
    {
        $stock = self::find($stockId);

        if (!$stock) {
            return 0; // No stock entry exists
        }

        $incomingStock = $stock->transactions()
            ->where('status', StockTransaction::STATUS_INCOMING_STOCK)
            ->sum('quantity');

        $outgoingStock = $stock->transactions()
            ->where('status', StockTransaction::STATUS_OUTGOING_STOCK)
            ->sum('quantity');

        return $incomingStock - $outgoingStock;
    }


    public static function getTotalStockByItem(int $itemId): int
    {


        $incomingStock = StockTransaction::where('status', StockTransaction::STATUS_INCOMING_STOCK)
            ->where('item_id', $itemId)
            ->sum('quantity');
        // Calculate outgoing stock
        $outgoingStock = StockTransaction::where('status', StockTransaction::STATUS_OUTGOING_STOCK)
            ->where('item_id', $itemId)
            ->sum('quantity');

        return round($incomingStock - $outgoingStock, 2);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class, 'stock_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
