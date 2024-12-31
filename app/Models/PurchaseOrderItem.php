<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{

    use HasFactory;
    protected $table = 'purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'source_company_id',
        'item_id',
        'uom_type_id',
        'quantity',
        'item_price',
        'total_price',
        'received_quantity',
        'order_date',
        'recieved_date',
        'status',

    ];
    const STATUS_PENDING = 1;

    const STATUS_CREATED = 2;
    const STATUS_RECIEVED = 3;
    const STATUS_PARTIAL_RECIEVED = 5;
    const STATUS_REJECTED = 4;


    public function getStatusLabel($status): string
    {
        switch ($status) {
            case PurchaseOrderItem::STATUS_PENDING:
                return '<span class="badge bg-warning">Pending</span>';
            case PurchaseOrderItem::STATUS_CREATED:
                return '<span class="badge bg-primary">Created</span>';
            case PurchaseOrderItem::STATUS_RECIEVED:
                return '<span class="badge bg-success">Received </span>';
            case PurchaseOrderItem::STATUS_REJECTED:
                return '<span class="badge bg-danger">Rejected</span>';
            case PurchaseOrderItem::STATUS_PARTIAL_RECIEVED:
                return '<span class="badge bg-warning">Partial Recieved</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(Purchaseorder::class, 'purchase_order_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
