<?php

namespace App\Imports;

use App\Models\PurchaseOrder;
use App\Models\SourceCompany;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseOrderImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $sourceCompany = SourceCompany::where('old_id', $row['source_company'])->firstOrFail();
        $formattedDate = Carbon::parse($row['date'])->format('Y-m-d');

        return new PurchaseOrder([


            'source_company_id' => $sourceCompany->id,



            'total_item' => 0,
            'total_quantity' => 0,
            'price' => 0,
            'creation_date' => $formattedDate,
        ]);
    }
}
