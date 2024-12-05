<?php

namespace App\Imports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseOrderImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PurchaseOrder([
            'source_company_id'=>$row['source_company'],
            'total_item'=>0,
            'total_quantity'=>0,
            'price'=>0,
            'creation_date'=>$row['date'],
        ]);
    }
}
