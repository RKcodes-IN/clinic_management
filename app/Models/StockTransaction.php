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
        'transaction_date',
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

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }


    public function invoiceDetail()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id');
    }


    public static function getBalanceStockByDate($stockId, $currentTransactionId)
    {
        // Fetch all transactions up to the current transaction ID
        $stockTransactions = StockTransaction::where('stock_id', $stockId)
            ->where('id', '<=', $currentTransactionId)->orderBy('id', 'asc')
            ->get();

        // Calculate total incoming and outgoing stock
        $totalIncomingStock = $stockTransactions
            ->where('status', self::STATUS_INCOMING_STOCK)
            ->sum('quantity');


        $totalOutgoingStock = $stockTransactions
            ->where('status', self::STATUS_OUTGOING_STOCK)
            ->sum('quantity');


        // Calculate balance stock
        $balanceStock = $totalIncomingStock - $totalOutgoingStock;

        return $balanceStock;
    }
}
