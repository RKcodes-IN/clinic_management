<?php

namespace App\Imports;

use App\Models\InteraktCallback;
use App\Models\PatientDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SendInteraktMessageImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Call the function and store its result in a variable.

        // Check if the interakt_callback table has a record with the contact number and status Delivered
        // $callback = InteraktCallback::where('phone_number', $row['contact_number'])
        //     ->first();

        // if ($callback && ($callback->status == 'Delivered' || $callback->status == 'Read'|| $callback->status == 'Sent')) {
        //     // Skip processing this row if already delivered or read.
        //     return;
        // }




        $patient = PatientDetail::where('phone_number', $row['contact_number'])->firstOrCreate();

        // Process the row (e.g., send patient update)
        sendPatientUpdationForm(
            $row['country_code'],
            $patient->name ?? "",
            $row['contact_number'],
            $patient->id ?? 0
        );
    }


    public function chunkSize(): int
    {
        return 500; // Adjust this value based on your server capacity

    }
}
