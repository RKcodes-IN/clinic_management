<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\SourceCompany;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Handle Company
        $company = SourceCompany::firstOrCreate(
            ['name' => $row['company_name']],
            ['created_at' => now(), 'updated_at' => now()]
        );


        // Handle Brand
        $brand = Brand::firstOrCreate(
            ['name' => $row['brand']],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Handle Category
        $category = Category::firstOrCreate(
            ['name' => $row['category']],
            ['created_at' => now(), 'updated_at' => now()]
        );


        // Create the Item
        return new Item([
            'item_code' => $row['item_code'],
            'item_type' => 1,
            'uom_type' => 0,
            'name' => $row['name'],
            'source_company' => $company->id,
            'brand' => $brand->id,
            'category' => $category->id,
            'ideal_quantity' => $row['ideal_stock_alerts'],
            'status'=>1
        ]);
    }
}
