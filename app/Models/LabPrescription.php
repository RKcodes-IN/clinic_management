<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'item_id',
        'quantity',
        'stock_id',
        'date',
        'description',
        'sample_type_id',
        'sample_taken',
        'report_available',
        'report_url',
        'out_of_range',
    ];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'id', 'stock_id');
    }

    public function patient()
    {
        return $this->hasOne(PatientDetail::class, 'id', 'patient_id');
    }
    public function sampleType()
    {
        return $this->hasOne(SampleType::class, 'id', 'sample_type_id');
    }
}
