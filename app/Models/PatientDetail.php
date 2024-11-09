<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'date_of_birth',
        'address',
        'place',
        'city',
        'state',
        'country',
        'whatsapp_no',
        'age',
        'gender',
        'pincode',
    ];
    const ACTIVE = 1;
    const INACTIVE = 2;
    const DELETE = 3;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
