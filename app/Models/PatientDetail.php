<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDetail extends Model
{
    use HasFactory;
    const ACTIVE = 1;
    const INACTIVE = 2;
    const DELETE = 3;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
