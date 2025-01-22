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
        'brand_id',
        'category_id',
        'max_discount_percentage',
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

    public static function getTypeLabel($type)
    {
        return match ($type) {
            self::TYPE_PHARMACY => 'Pharmacy',
            self::TYPE_LAB => 'Lab',
            self::MISCELLANEOUS => 'Miscellaneous',
            default => 'Unknown',
        };
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


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(SourceCompany::class, 'source_company', 'id'); // Ensure 'source_company' is the foreign key in your `items` table
    }
    public function uom()
    {
        return $this->belongsTo(UomType::class, 'uom_type', 'id'); // Ensure 'source_company' is the foreign key in your `items` table
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'item_id');
    }
}
