<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;



    const STATUS_PENDING =1;

    const STATUS_CREATED =2;
    const STATUS_RECIEVED =3;
    const STATUS_REJECTED =4;

    public function getStatusLabel($status): string
    {
        switch ($status) {
            case PurchaseOrder::STATUS_PENDING:
                return 'Pending';
            case PurchaseOrder::STATUS_CREATED:
                return 'Created';
            case PurchaseOrder::STATUS_RECIEVED:
                return 'Received';
            case PurchaseOrder::STATUS_REJECTED:
                return 'Rejected';
            default:
                return 'Unknown';
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
