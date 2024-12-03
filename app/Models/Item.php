<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    const TYPE_PHARMACY = 1;
    const TYPE_LAB = 2;

    const MISCELLANEOUS = 3;
    protected $fillable = [
        'item_code',
        'name',
        'item_type',
        'uom_type',
        'brand',
        'category',
        'source_company',
        'alert_quantity',
        'status',
    ];
    /**
     * Get item types as an array for dropdown selection.
     *
     * @return array
     */
    public static function getItemTypes()
    {
        return [
            self::TYPE_PHARMACY => "Pharmacy",
            self::TYPE_LAB => "Laboratory",
            self::MISCELLANEOUS => "Miscellaneous"
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand', 'id');
    }

    public function company()
    {
        return $this->belongsTo(SourceCompany::class, 'source_company', 'id');
    }
}
