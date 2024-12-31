<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    const PAYMENT_STATUS_PENGING = 0;
    const PAYMENT_STATUS_PAID = 1;
    const PAYMENT_PARTIAL_PAYMENT = 4;
    const PAYMENT_STATUS_FAILED = 3;

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
    public static function getPaymentStatusLabel(?int $status): string
    {
        return match ($status) {
            self::PAYMENT_STATUS_PENGING => 'Pending',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_FAILED => 'Failed',
            self::PAYMENT_PARTIAL_PAYMENT => 'Partial Payment',

            default => 'Unknown', // Handle null or invalid values
        };
    }

    public static function getPaymentStatusDropdown(): array
    {
        return [
            self::PAYMENT_STATUS_PENGING => 'Pending',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_FAILED => 'Failed',
            self::PAYMENT_PARTIAL_PAYMENT => 'Partial Payment',
        ];
    }
    public function patient()
    {
        return $this->belongsTo(PatientDetail::class, 'paitent_id');
    }

    // Relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(DoctorDetail::class, 'doctor_id');
    }

    // Relationship with InvoiceDetails
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
}
