<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteraktCallback extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'status',
        'failed_reason',
        'message_id',
        'received_at',
        'full_json'
    ];

    // If using a JSON column and you want it automatically casted:
    protected $casts = [
        'full_json' => 'array',
    ];
}
