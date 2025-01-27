<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\SourceCompany;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\UomType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;



class ItemsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            $company = SourceCompany::where(
                'old_id',
                $row['company_name']
            )->first();

            if ($company) {
                $compant_id = $company->id;
            } else {
                $compant_id = 0;
            }

            // Handle Brand
            $brand = Brand::where(
                'old_id',
                $row['brand'],
            )->first();

            if ($brand) {
                $brand_id = $brand->id;
            } else {
                $brand_id = 0;
            }
            // Handle Category


            // Handle UOM
            $uom = UomType::where(
                'old_id',
                $row['uom'],
            )->first();
            if ($brand) {
                $uom_id = $uom->id;
            } else {
                $uom_id = 0;
            }
            // Create or find the Item
            $item = Item::firstOrCreate(
                ['item_code' => $row['item_code']],
                [
                    'item_type' => $row['item_type'],
                    'uom_type' => $uom_id,
                    'item_code' => $row['item_code'],
                    'name' => $row['name'],
                    'source_company' => $compant_id,
                    'ideal_quantity' => $row['ideal_stock_alerts'],
                    'alert_quantity' => $row['alert_quantity'],
                    'brand_id' => $brand_id,
                    'category_id' => 0,
                    'status' => 1
                ]
            );




            DB::commit();

            return $item;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Parse a date string with fallback for invalid formats.
     *
     * @param string|null $date
     * @param string $inputFormat
     * @param string $outputFormat
     * @return string|null
     */
    private function parseDate($date, $inputFormat = 'd-m-Y', $outputFormat = 'Y-m-d H:i:s')
    {
        if (empty($date)) {
            return null;
        }

        try {
            return Carbon::createFromFormat($inputFormat, $date)->format($outputFormat);
        } catch (\Exception $e) {
            return null;
        }
    }
}
