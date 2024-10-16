<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'email',
        'phone_number',
        'address',
        'is_previous_report_available',
        'main_complaint',
        'available_date',
        'time_from',
        'time_to',
        'message',
        'status',
        'patient_name', // Add 'patient_name' to the fillable attributes
    ];
    const STATUS_NOT_CONFIRMED = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;


    public static function getStatusLabels()
    {
        return [
            self::STATUS_NOT_CONFIRMED => 'Not Confirmed',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(PatientDetail::class);
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorDetail::class);
    }


    public static function getStatusLabel($status)
    {
        switch ($status) {
            case self::STATUS_NOT_CONFIRMED:
                return '<span class="badge bg-warning">Not Confirmed</span>';
            case self::STATUS_CONFIRMED:
                return '<span class="badge bg-success">Confirmed</span>';
            case self::STATUS_COMPLETED:
                return '<span class="badge bg-success">Completed</span>';
            case self::STATUS_CANCELLED:
                return '<span class="badge bg-danger">Cancelled</span>';
            default:
                return 'Unknown';
        }
    }
}
