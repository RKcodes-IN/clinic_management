<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceCompany extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'old_id',
        'address',
        'email',
        'phone_one',
        'phone_two',
        'gst_no',
        'contact_person',
        'status',

    ];
}
