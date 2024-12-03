<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationReportValues extends Model
{
    use HasFactory;


    protected $fillable = [
        'investigation_report_id',
        'investigation_report_type_id',
        'value',
        'out_of_range',
    ];

    public function reportType()
    {
        return $this->belongsTo(InvestigationReportType::class, 'investigation_report_type_id', 'id');
        // 'patiend_id' is the foreign key in this model
        // 'id' is the primary key in the related Patient model
    }
}
