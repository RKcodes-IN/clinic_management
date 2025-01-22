<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemExport implements FromCollection, WithHeadings
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }


    public function collection(): Collection
    {
        // Extract and clean category IDs
        $categoryIds = $this->items->pluck('category_id')
            ->reject(function ($value) {
                return is_null($value) || empty($value);
            })
            ->map(function ($item) {
                // Handle both string/integer IDs and potential arrays
                if (is_array($item)) {
                    return (int) head($item);
                }
                return (int) $item;
            })
            ->filter()  // Remove any zero values after casting
            ->unique()
            ->values()
            ->toArray();

        // Extract and clean brand IDs using the same logic
        $brandIds = $this->items->pluck('brand')
            ->reject(function ($value) {
                return is_null($value) || empty($value);
            })
            ->map(function ($item) {
                if (is_array($item)) {
                    return (int) head($item);
                }
                return (int) $item;
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Fetch related data only if we have valid IDs
        $categories = !empty($categoryIds)
            ? DB::table('categories')->whereIn('id', $categoryIds)->pluck('name', 'id')->toArray()
            : [];

        $brands = !empty($brandIds)
            ? DB::table('brands')->whereIn('id', $brandIds)->pluck('name', 'id')->toArray()
            : [];

        // Map items to final export format
        return $this->items->map(function ($item) use ($brands, $categories) {
            // Ensure category and brand keys are valid types
            $categoryKey = is_scalar($item->category) ? (string) $item->category : "";
            $brandKey = is_scalar($item->brand) ? (string) $item->brand : "";

            return [
                'ID' => $item->id,
                'Item Code' => $item->item_code,
                'Name' => $item->name,
                'Category' => $categories[$categoryKey] ?? "",
                'Source Company' => $item->company->name ?? "",
                'Brand' => $brands[$brandKey] ?? "",
            ];
        });
    }


    public function headings(): array
    {
        return ['ID', 'Item Code', 'Item Name', 'Category', 'Source Company', 'Brand'];
    }
}
