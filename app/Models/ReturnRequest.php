<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'patient_id',
        'return_code',
        'return_date',
        'return_status',
        'return_quantity',
        'return_reason',
        'item_id',
        'unit_price',
        'return_amount',
        'created_by'
    ];

    public function items()
    {
        return $this->hasMany(ReturnRequestItem::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function patient()
    {
        return $this->belongsTo(PatientDetail::class, 'patient_id');
    }
}
