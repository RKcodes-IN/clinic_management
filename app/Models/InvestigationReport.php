<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationReport extends Model
{
    use HasFactory;

    public function patient()
    {
        return $this->belongsTo(PatientDetail::class, 'patient_id', 'id');
        // 'patiend_id' is the foreign key in this model
        // 'id' is the primary key in the related Patient model
    }
}
