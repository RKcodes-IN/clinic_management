<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousMedication extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'appointment_id', 'medicine_name', 'chemical_id'];

    public function patient()
    {
        return $this->belongsTo(PatientDetail::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function chemical()
    {
        return $this->belongsTo(Chemical::class, 'chemical_id', 'code');
    }
}
