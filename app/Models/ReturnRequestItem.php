<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_request_id',
        'item_id',
        'invoice_detail_id',
        'unit_price',
        'item_id',
        'quantity',
        'unit_price',
        'total_amount',
        'reason'
    ];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
