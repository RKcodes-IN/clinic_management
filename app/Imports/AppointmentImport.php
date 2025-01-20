<?php

namespace App\Imports;

use App\Models\Appointment;
use App\Models\PatientDetail;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AppointmentImport implements ToModel, WithHeadingRow
{
    /**
     * Format date string to Y-m-d format
     *
     * @param string|null $date
     * @return string|null
     */


    /**
     * Format time string to 24-hour format (H:i)
     *
     * @param string|null $time
     * @return string|null
     */
    private function formatTime(?string $time): ?string
    {
        if (!$time) return null;

        try {
            // Normalize AM/PM case for consistency
            $time = strtolower(str_replace(' ', '', $time));

            // Use Carbon to parse the time string
            return Carbon::parse($time)->format('H:i');
        } catch (\Exception $e) {
            // Handle invalid time strings gracefully
            return null;
        }
    }



    /**
     * Map the data row to the Appointment model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if the email already exists in the User table
        $existingUser = User::where('email', $row['email'])->first();

        try {
            $availableDate = isset($row['date']) && is_numeric($row['date'])
                ? Date::excelToDateTimeObject($row['date'])->format('Y-m-d')
                : null;
        } catch (\Exception $e) {
            $availableDate = null; // Fallback to null
        }

        // Use `created_at` if `available_date` is null, empty, or invalid
        $availableDate = empty($availableDate) || $availableDate === '00:00:00'
            ? $row['created_at']
            : $availableDate;

        // Ensure a unique email for new users
        if ($existingUser) {
            $row['email'] = $row['email'] . '_' . uniqid() . '@example.com';
        }

        // Attempt to find an existing PatientDetail by phone number
        $patientDetail = PatientDetail::where('phone_number', $row['phone'])->first();

        if (!$patientDetail) {
            $role = Role::where('name', 'patient')->firstOrFail();

            // Create a new User
            $user = User::create([
                'name'     => $row['name'],
                'email'    => $row['email'],
                'phone'    => $row['phone'],
                'user_role' => $role->name,
            ]);

            $user->assignRole($role);

            // Create a new PatientDetail with formatted date
            $patientDetail = PatientDetail::create([
                'name'     => $row['name'],
                'email'    => $row['email'],
                'user_id'  => $user->id,
                'phone_number' => $row['phone'],
                'alt_phone_number' => $row['phone'],
                'available_date' => $availableDate,
                'date_of_birth' => "", // Default date
                'address'  => $row['area_of_residence'] ?? null,
                'image'  => "",
                'gender'  => "",
                'place'  =>  $row['area_of_residence'],
                'city'  =>  "",
                'state'  =>  "",
                'country'  =>  "",
                'whatsapp_no'  =>  $row['phone'],
                'age'  =>  "",
                'pincode'  =>  "",
                'status'  =>  1,
                'created_at'  => $row['created_at'],
            ]);
        }

        $whatsapp = ($row['whatsapp'] == "checked") ? "yes" : "no";
        $previous_reports_available = ($row['previous_reports_available'] == "checked") ? 1 : 2;
        $is_online = ($row['visit_to_clinic'] == "checked") ? 2 : 1;

        $status = match ($row['status']) {
            0 => Appointment::STATUS_NOT_CONFIRMED,
            1 => Appointment::STATUS_CONFIRMED,
            default => Appointment::STATUS_CANCELLED,
        };

        // Return a new Appointment instance with formatted dates and times
        return new Appointment([
            'old_appointment_id' => $row['id'],
            'patient_id' => $patientDetail->id,
            'phone_number' => $row['phone'],
            'whatsapp' => $whatsapp,
            'email' => $row['email'],
            'address' => $row['area_of_residence'],
            'available_date' => $availableDate,
            'message' => $row['message'],
            'time' => $this->formatTime($row['time']),
            'time_from' => $this->formatTime($row['time_from']),
            'time_to' => $this->formatTime($row['time_to']),
            'confirmation_date' => $availableDate, // Use the same logic for confirmation_date
            'previous_reports_available' => $previous_reports_available,
            'main_complaint' => $row['main_complaint'],
            'is_online' => $is_online,
            'visit_to_clinic' => $row['visit_to_clinic'],
            'created_at' => $row['created_at'],
            'status' => $status,
        ]);
    }
}
