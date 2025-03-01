<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'sub_category',
        'item_id',
        'appointment_id',
        'created_at',
        'updated_at',
        'status',
        'material',
        'time_from',
        'time_to',
        'application_area'

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
}
