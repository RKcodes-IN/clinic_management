<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPastHistory extends Model
{
    use HasFactory;

    public function pastHistory()
    {
        return $this->belongsTo(PastHistory::class, 'past_histroy_id', 'id');

    }
}
