<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UomType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'old_id',
        'created_by',
        'created_at',
        'updated_at',
        'status',
    ];
}
