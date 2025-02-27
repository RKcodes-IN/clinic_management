<?php

namespace App\Imports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MainComplaintImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $appointment = Appointment::where('id', $row['id'])->first();

        if (!$appointment) {
            // Handle the case where the appointment is not found.
            // For example, skip this row:
            return null;
            // Or you might want to log an error:
            // Log::error("Appointment with id {$row['id']} not found.");
        }

        $appointment->address = isset($row['address']) ? $row['address'] : "";
        $appointment->age = $row['age'];
        $appointment->country = $row['country'];
        $appointment->city = $row['city'];
        $appointment->main_complaint = $row['main_complaint'];
        $appointment->save();

        return $appointment;
    }
}
