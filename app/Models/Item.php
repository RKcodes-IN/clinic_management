<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    const TYPE_PHARMACY = 1;
    const TYPE_LAB = 3;

    const MISCELLANEOUS = 2;
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


    public static function getTotalStockByItem(int $itemId): int
    {
        // Correct usage of where clause for filtering by item_id
        $incomingStock = StockTransaction::where('status', StockTransaction::STATUS_INCOMING_STOCK)
            ->where('item_id', $itemId) // Separate column and value
            ->sum('quantity');

        $outgoingStock = StockTransaction::where('status', StockTransaction::STATUS_OUTGOING_STOCK)
            ->where('item_id', $itemId) // Separate column and value
            ->sum('quantity');

        return $incomingStock - $outgoingStock;
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
