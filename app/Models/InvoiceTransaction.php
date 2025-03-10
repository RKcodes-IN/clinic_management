<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'patient_id',
        'amount',
        'payment_mode',
        'payment_date',
        'status',
    ];
}
