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
        // Get IDs for brands and categories from items
        $brandIds = $this->items->pluck('brand')->unique();
        $categoryIds = $this->items->pluck('category')->unique();

        // Fetch related names from the database
        $brands = DB::table('brands')->whereIn('id', $brandIds)->pluck('name', 'id');
        $categories = DB::table('categories')->whereIn('id', $categoryIds)->pluck('name', 'id');
        // Map the items collection to include resolved brand and category names
        return $this->items->map(function ($item) use ($brands, $categories) {

            return [
                'ID' => $item->id,
                'Item Code' => $item->item_code,
                'Name' => $item->name,
                'Category' => $categories[$item->category]??"", // Lookup category name
                'Source Company' => $item->company->name??"",
                'Brand' => $brands[$item->brand]??"", // Lookup brand name
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Item Code', 'Item Name', 'Category', 'Source Company', 'Brand'];
    }
}
