<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\PatientDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $paitentmobile = $row["patient_mobile"];


        $paitentDetails = PatientDetail::where("phone_number", $paitentmobile)->first();

        if ($paitentDetails) {
            return new Invoice([
                "paitent_id"=> $paitentDetails->id,
                "old_invoice_id"=> $row["old_invoice_id"],
                "appointment_id"=> 0,
                "invoice_number"=> $row["invoice_number"],
                "date"=> $row["invoice_date"],
                "sub_total"=> $row["sub_total"],
                "others"=> $row["others"],
                "total"=> $row["total"],
                "recieved_amount"=> $row["received"],
                "pending_amount"=> $row["pending"],
                "payment_status"=> $row["paid_status"],
                "approved_by"=> $row["approved_by"],
                "status"=> $row["status"],
                "created_by"=> $row["created_by"],
                "created_at"=> $row["created_at"],
            ]);
        }
    }
}
