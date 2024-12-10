<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'paitent_id',
        'old_invoice_id',
        'appointment_id',
        'invoice_number',
        'date',
        'doctor_id',
        'sub_total',
        'other',
        'total',
        'recieved_amount',
        'pending_amount',
        'payment_status',
        'status',
        'approved_by',
        'created_by',
        'created_at',
        'updated_at',
    ];
    public function paitent()
    {
        return $this->belongsTo(PatientDetail::class, 'paitent_id');
    }
}
