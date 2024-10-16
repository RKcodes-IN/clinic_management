<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthEvaluation extends Model
{
    use HasFactory;


    const smoking = 'smoking';
    const alcohol = 'alcohol';
    const gutka = 'gutka';
    const tea = 'tea';
    const coffee = 'coffee';
    const anyother = 'anyother';


    const CABG = "CABG(By Pass)";
    const PTCA = "PTCA(Stenting)";
    const Apeendix = "Apeendix";
    const GallBladder = "Gall Bladder";
    const Hystectctomy = "Hystectctomy";
    const Caesarial = "Caesarial";
    const Anyother = "AnyO Other";

    public function patient()
    {
        return $this->belongsTo(PatientDetail::class, 'patient_id', 'id');
        // 'patiend_id' is the foreign key in this model
        // 'id' is the primary key in the related Patient model
    }
}
