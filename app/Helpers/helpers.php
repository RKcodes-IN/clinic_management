
<?php

use App\Models\Appointment;
use Carbon\Carbon;


if (!function_exists('appointment_counts')) {
    function appointmentCounts()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $today = Carbon::today();

        // Helper function to get appointment counts based on filters
        $getAppointmentCount = function ($status = null, $dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = Appointment::query();

            if ($status !== null) {
                $query->where('status', $status);
            }

            if ($dateFilter === 'today') {
                $query->whereDate('created_at', $today);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', $currentMonth)
                      ->whereYear('created_at', $currentYear);
            }

            return $query->count();
        };

        // Total appointments
        $totalAppointments = $getAppointmentCount();

        // This month's appointments
        $thisMonthAppointments = $getAppointmentCount(null, 'this_month');

        // Today's appointments
        $todayAppointments = $getAppointmentCount(null, 'today');

        // Not confirmed appointments
        $totalAppointmentsNew = $getAppointmentCount(Appointment::STATUS_NOT_CONFIRMED);
        $thisMonthAppointmentsNew = $getAppointmentCount(Appointment::STATUS_NOT_CONFIRMED, 'this_month');
        $todayAppointmentsNew = $getAppointmentCount(Appointment::STATUS_NOT_CONFIRMED, 'today');

        // Confirmed appointments
        $totalAppointmentsReview = $getAppointmentCount(Appointment::STATUS_CONFIRMED);
        $thisMonthAppointmentsReview = $getAppointmentCount(Appointment::STATUS_CONFIRMED, 'this_month');
        $todayAppointmentsReview = $getAppointmentCount(Appointment::STATUS_CONFIRMED, 'today');

        // Helper function to count patients with two or more appointments
        $getPatientsWithTwoOrMore = function ($dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = Appointment::select('patient_id')
                ->groupBy('patient_id')
                ->havingRaw('COUNT(*) >= 2');

            if ($dateFilter === 'today') {
                $query->whereDate('created_at', $today);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', $currentMonth)
                      ->whereYear('created_at', $currentYear);
            }

            return $query->count();
        };

        // Patients with two or more appointments
        $patientsWithTwoOrMoreToday = $getPatientsWithTwoOrMore('today');
        $patientsWithTwoOrMoreThisMonth = $getPatientsWithTwoOrMore('this_month');
        $patientsWithTwoOrMoreTotal = $getPatientsWithTwoOrMore();

        // Prepare the data array
        $data = [
            'totalAppointments' => $totalAppointments,
            'thisMonthAppointments' => $thisMonthAppointments,
            'todayAppointments' => $todayAppointments,
            'totalAppointmentsNew' => $totalAppointmentsNew,
            'thisMonthAppointmentsNew' => $thisMonthAppointmentsNew,
            'todayAppointmentsNew' => $todayAppointmentsNew,
            'totalAppointmentsReview' => $totalAppointmentsReview,
            'thisMonthAppointmentsReview' => $thisMonthAppointmentsReview,
            'todayAppointmentsReview' => $todayAppointmentsReview,
            'patientsWithTwoOrMoreToday' => $patientsWithTwoOrMoreToday,
            'patientsWithTwoOrMoreThisMonth' => $patientsWithTwoOrMoreThisMonth,
            'patientsWithTwoOrMoreTotal' => $patientsWithTwoOrMoreTotal,
        ];

        // Return the data in JSON format
        return $data;
    }

}
