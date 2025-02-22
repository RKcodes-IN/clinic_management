<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdatedPatientDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'dob',
        'email',
        'patient_id',
        'alt_country_code',
        'country_code',
        'phone_number1',
        'alt_contact',
        'address',
        'city',
        'country',
        'consulting_since',
        'casesheet_available',
        'pincode',
        'image'
    ];
}
