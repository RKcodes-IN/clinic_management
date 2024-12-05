<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'source_company_id',
        'item_id',
        'uom_type_id',
        'quantity',
        'item_price',
        'total_price',
        'order_date',
        'recieved_date',
        'status',

    ];
    const STATUS_PENDING = 1;

    const STATUS_CREATED = 2;
    const STATUS_RECIEVED = 3;
    const STATUS_REJECTED = 4;


    public function purchaseOrder()
    {
        return $this->belongsTo(Purchaseorder::class, 'purchase_order_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
