<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    const STATUS_INCOMING_STOCK = 1;
    const STATUS_OUTGOING_STOCK = 2;

    use HasFactory;
    protected $fillable = [
        'stock_id',
        'item_id',
        'invoice_id',
        'purchase_order_id',
        'quantity',
        'item_price',
        'total_price',
        'status',
        'created_by',
        'updated_by',
    ];

    public static function calculateTotalStockWithExpiry($stockId, $expiryDate)
    {
        // Total incoming stock for the given stock ID and expiry date
        $incomingStock = self::where('stock_id', $stockId)
            ->where('status', self::STATUS_INCOMING_STOCK)
            ->whereDate('created_at', '<=', $expiryDate)
            ->sum('quantity');

        // Total outgoing stock for the given stock ID and expiry date
        $outgoingStock = self::where('stock_id', $stockId)
            ->where('status', self::STATUS_OUTGOING_STOCK)
            ->whereDate('created_at', '<=', $expiryDate)
            ->sum('quantity');

        // Calculate net stock
        return $incomingStock - $outgoingStock;
    }

    /**
     * Calculate total stock for a specific stock ID.
     *
     * @param int $stockId
     * @return int
     */
    public static function calculateTotalStock($stockId)
    {
        // Total incoming stock
        $incomingStock = self::where('stock_id', $stockId)
            ->where('status', self::STATUS_INCOMING_STOCK)
            ->sum('quantity');

        // Total outgoing stock
        $outgoingStock = self::where('stock_id', $stockId)
            ->where('status', self::STATUS_OUTGOING_STOCK)
            ->sum('quantity');

        // Calculate net stock
        return $incomingStock - $outgoingStock;
    }
}
