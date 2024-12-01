<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;



    const STATUS_PENDING = 1;

    const STATUS_CREATED = 2;
    const STATUS_RECIEVED = 3;
    const STATUS_REJECTED = 4;

    public function getStatusLabel($status): string
    {
        switch ($status) {
            case PurchaseOrder::STATUS_PENDING:
                return '<span class="badge bg-warning">Pending</span>';
            case PurchaseOrder::STATUS_CREATED:
                return '<span class="badge bg-primary">Created</span>';
            case PurchaseOrder::STATUS_RECIEVED:
                return '<span class="badge bg-success">Received All</span>';
            case PurchaseOrder::STATUS_REJECTED:
                return '<span class="badge bg-danger">Rejected</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id', 'id');
    }


    public function sourceCompany()
    {
        return $this->hasOne(SourceCompany::class, 'id', 'source_company_id');
    }
}
