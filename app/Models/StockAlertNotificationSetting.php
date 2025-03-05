<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlertNotificationSetting extends Model
{
    use HasFactory;
    protected $fillable = ['frequency', 'time_of_day', 'day_of_week', 'day_of_month'];
}
