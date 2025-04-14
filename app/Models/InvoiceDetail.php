<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'old_invoice_id',
        'old_invoice_detail_id',
        'paitent_id',
        'stock_id',
        'item_id',
        'item_type',
        'item_price',
        'discount_amount',
        'add_dis_amount',
        'add_dis_percent',
        'total_amount',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Relationship with Stock
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'stock_id', 'stock_id');
    }
}
