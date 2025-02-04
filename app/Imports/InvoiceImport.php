<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\PatientDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            $patientMobile = $row["patient_mobile"];
            $patientDetails = PatientDetail::where("phone_number", $patientMobile)->first();

            if (!$patientDetails) {
                return null; // Skip if patient not found
            }

            // Parse date using strtotime()
            $excelDate = $row["invoice_date"];
            $parsedDate = $this->parseDate($excelDate);

            return new Invoice([
                "paitent_id" => $patientDetails->id,
                "old_invoice_id" => $row["old_invoice_id"],
                "appointment_id" => 0,
                "invoice_number" => $row["invoice_number"],
                "date" => $parsedDate,
                "doctor_id" => 1,
                "sub_total" => $row["sub_total"],
                "others" => $row["others"],
                "total" => $row["total"],
                "recieved_amount" => $row["received"],
                "pending_amount" => $row["pending"],
                "payment_status" => $row["paid_status"],
                "approved_by" => $row["approved_by"],
                "status" => $row["status"],
                "created_by" => $row["created_by"],
                "created_at" => now(),
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseDate($excelDate)
    {
        try {
            // Handle empty dates
            if (empty($excelDate)) {
                return null;
            }

            // Convert Excel date to timestamp
            $timestamp = strtotime($excelDate);

            if ($timestamp === false) {
                // Log invalid dates
                return null;
            }

            // Format to Y-m-d
            return date('Y-m-d', $timestamp);
        } catch (\Exception $e) {
            return null;
        }
    }
}
