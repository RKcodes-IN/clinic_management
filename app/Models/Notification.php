<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'message',
        'redirect_url',
        'notification_type',
        'user_id',
        'status',
    ];

    const TYPE_STOCK_ALERT = 1;

    const STATUS_NOT_READ =1;
    const STATUS_READ =2;


}
