
<?php

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\PatientDetail;
use Carbon\Carbon;


if (!function_exists('appointmentCounts')) {
    function appointmentCounts()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $today = Carbon::today();

        // Helper function to get appointment counts based on filters
        $getAppointmentCount = function ($type = null, $dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = Appointment::query();

            if ($type !== null) {
                $query->where('type', $type);
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
        $totalAppointmentsNew = $getAppointmentCount(Appointment::NEW);
        $thisMonthAppointmentsNew = $getAppointmentCount(Appointment::NEW, 'this_month');
        $todayAppointmentsNew = $getAppointmentCount(Appointment::NEW, 'today');

        // Confirmed appointments
        $totalAppointmentsReview = $getAppointmentCount(Appointment::REVIEW);
        $thisMonthAppointmentsReview = $getAppointmentCount(Appointment::REVIEW, 'this_month');
        $todayAppointmentsReview = $getAppointmentCount(Appointment::REVIEW, 'today');

        // Helper function to count patients with two or more appointments

        // Patients with two or more appointments
        $patientsWithTwoOrMoreToday = $getAppointmentCount(Appointment::REVISIT, 'today');
        $patientsWithTwoOrMoreThisMonth = $getAppointmentCount(Appointment::REVISIT, 'this_month');
        $patientsWithTwoOrMoreTotal = $getAppointmentCount(Appointment::REVISIT);

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


if (!function_exists('patientGenderCounts')) {
    function patientGenderCounts()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $today = Carbon::today();

        // Helper function to get patient counts based on filters
        $getPatientCount = function ($gender = null, $dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = PatientDetail::query();

            if ($gender !== null) {
                $query->where('gender', $gender);
            }

            if ($dateFilter === 'today') {
                $query->whereDate('created_at', $today);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            }

            return $query->count();
        };

        // Total patients (regardless of gender)
        $totalPatients = $getPatientCount();
        $thisMonthPatients = $getPatientCount(null, 'this_month');
        $todayPatients = $getPatientCount(null, 'today');

        // Male patients
        $totalMalePatients = $getPatientCount('Male');
        $thisMonthMalePatients = $getPatientCount('Male', 'this_month');
        $todayMalePatients = $getPatientCount('Male', 'today');

        // Female patients
        $totalFemalePatients = $getPatientCount('Female');
        $thisMonthFemalePatients = $getPatientCount('Female', 'this_month');
        $todayFemalePatients = $getPatientCount('Female', 'today');

        // Calculate percentages
        $calculatePercentage = function ($part, $total) {
            return $total > 0 ? round(($part / $total) * 100, 2) : 0;
        };

        // Prepare the data array
        $data = [
            'total' => [
                'all' => $totalPatients,
                'male' => $totalMalePatients,
                'female' => $totalFemalePatients,
                'malePercentage' => $calculatePercentage($totalMalePatients, $totalPatients),
                'femalePercentage' => $calculatePercentage($totalFemalePatients, $totalPatients)
            ],
            'thisMonth' => [
                'all' => $thisMonthPatients,
                'male' => $thisMonthMalePatients,
                'female' => $thisMonthFemalePatients,
                'malePercentage' => $calculatePercentage($thisMonthMalePatients, $thisMonthPatients),
                'femalePercentage' => $calculatePercentage($thisMonthFemalePatients, $thisMonthPatients)
            ],
            'today' => [
                'all' => $todayPatients,
                'male' => $todayMalePatients,
                'female' => $todayFemalePatients,
                'malePercentage' => $calculatePercentage($todayMalePatients, $todayPatients),
                'femalePercentage' => $calculatePercentage($todayFemalePatients, $todayPatients)
            ]
        ];

        return $data;
    }
}


if (!function_exists('invoiceCount')) {
    function invoiceCount()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $today = Carbon::today();

        // Helper function to get invoice counts based on payment status and date filters
        $getInvoiceCount = function ($status = null, $dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = Invoice::query();

            if ($status !== null) {
                $query->where('payment_status', $status);
            }

            if ($dateFilter === 'today') {
                $query->whereDate('created_at', $today);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            }

            return $query->count();
        };

        // Total invoices
        $totalInvoice = $getInvoiceCount();
        $totalPaidInvoice = $getInvoiceCount(Invoice::PAYMENT_STATUS_PAID);
        $totalPendingInvoice = $getInvoiceCount(Invoice::PAYMENT_STATUS_PENGING);
        $totalPartialInvoice = $getInvoiceCount(Invoice::PAYMENT_PARTIAL_PAYMENT);

        // This month invoices
        $thisMonthInvoice = $getInvoiceCount(null, 'this_month');
        $thisMonthPaidInvoice = $getInvoiceCount(Invoice::PAYMENT_STATUS_PAID, 'this_month');
        $thisMonthPendingInvoice = $getInvoiceCount(Invoice::PAYMENT_STATUS_PENGING, 'this_month');
        $thisMonthPartialInvoice = $getInvoiceCount(Invoice::PAYMENT_PARTIAL_PAYMENT, 'this_month');

        // Today invoices
        $todayInvoice = $getInvoiceCount(null, 'today');
        $todayPaidInvoice = $getInvoiceCount(Invoice::PAYMENT_STATUS_PAID, 'today');
        $todayPendingInvoice = $getInvoiceCount(Invoice::PAYMENT_STATUS_PENGING, 'today');
        $todayPartialInvoice = $getInvoiceCount(Invoice::PAYMENT_PARTIAL_PAYMENT, 'today');

        // Prepare the data array
        $data = [
            'total' => [
                'all' => $totalInvoice,
                'paid' => $totalPaidInvoice,
                'pending' => $totalPendingInvoice,
                'partial' => $totalPartialInvoice,
            ],
            'thisMonth' => [
                'all' => $thisMonthInvoice,
                'paid' => $thisMonthPaidInvoice,
                'pending' => $thisMonthPendingInvoice,
                'partial' => $thisMonthPartialInvoice,
            ],
            'today' => [
                'all' => $todayInvoice,
                'paid' => $todayPaidInvoice,
                'pending' => $todayPendingInvoice,
                'partial' => $todayPartialInvoice,
            ],
        ];

        return $data;
    }
}


if (!function_exists('invoiceAmount')) {
    function invoiceAmount()
    {
        $today = Carbon::today();

        // Determine the financial year start and end dates (1st April to 31st March)
        if ($today->month >= 4) {
            $financialYearStart = Carbon::createFromDate($today->year, 4, 1);
            $financialYearEnd   = Carbon::createFromDate($today->year + 1, 3, 31);
        } else {
            $financialYearStart = Carbon::createFromDate($today->year - 1, 4, 1);
            $financialYearEnd   = Carbon::createFromDate($today->year, 3, 31);
        }

        // Current month and year (for "thisMonth" and "today" calculations)
        $currentMonth = $today->month;
        $currentYear  = $today->year;

        // Helper closure for summing a column with optional date filtering
        $getSum = function ($status, $column, $dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = Invoice::query()->where('payment_status', $status);

            if ($dateFilter === 'today') {
                $query->whereDate('created_at', $today);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            }

            return $query->sum($column);
        };

        /**
         * Overall sums for the financial year (1st April - 31st March)
         * Exclude invoices with PAYMENT_STATUS_FAILED.
         */
        $totalInvoiceAmount = Invoice::whereBetween('date', [$financialYearStart, $financialYearEnd])
            ->where('payment_status', '!=', Invoice::PAYMENT_STATUS_FAILED)
            ->sum('total');

        $totalPaidInvoiceAmount = Invoice::whereBetween('date', [$financialYearStart, $financialYearEnd])
            ->where('payment_status', Invoice::PAYMENT_STATUS_PAID)
            ->sum('total');

        $totalPendingInvoiceAmount = Invoice::whereBetween('date', [$financialYearStart, $financialYearEnd])
            ->where('payment_status', Invoice::PAYMENT_STATUS_PENGING)
            ->sum('total')
            + Invoice::whereBetween('date', [$financialYearStart, $financialYearEnd])
            ->where('payment_status', Invoice::PAYMENT_PARTIAL_PAYMENT)
            ->sum('pending_amount');

        /**
         * This Month sums
         */
        $thisMonthInvoiceAmount = Invoice::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('total');

        $thisMonthPaidInvoiceAmount = Invoice::where('payment_status', Invoice::PAYMENT_STATUS_PAID)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('total');

        $thisMonthPendingInvoiceAmount = Invoice::where('payment_status', Invoice::PAYMENT_STATUS_PENGING)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('total')
            + Invoice::where('payment_status', Invoice::PAYMENT_PARTIAL_PAYMENT)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('pending_amount');

        /**
         * Today sums
         */
        $todayInvoiceAmount = Invoice::whereDate('date', $today)
            ->sum('total');

        $todayPaidInvoiceAmount = Invoice::where('payment_status', Invoice::PAYMENT_STATUS_PAID)
            ->whereDate('date', $today)
            ->sum('total');

        $todayPendingInvoiceAmount = Invoice::where('payment_status', Invoice::PAYMENT_STATUS_PENGING)
            ->whereDate('date', $today)
            ->sum('total')
            + Invoice::where('payment_status', Invoice::PAYMENT_PARTIAL_PAYMENT)
            ->whereDate('date', $today)
            ->sum('pending_amount');

        // Prepare the data array with sums for each category
        $data = [
            'total' => [
                'all'     => round($totalInvoiceAmount, 2),
                'paid'    => round($totalPaidInvoiceAmount, 2),
                'pending' => round($totalPendingInvoiceAmount, 2),
            ],
            'thisMonth' => [
                'all'     => $thisMonthInvoiceAmount,
                'paid'    => $thisMonthPaidInvoiceAmount,
                'pending' => $thisMonthPendingInvoiceAmount,
            ],
            'today' => [
                'all'     => $todayInvoiceAmount,
                'paid'    => $todayPaidInvoiceAmount,
                'pending' => $todayPendingInvoiceAmount,
            ],
        ];

        return $data;
    }
}


if (!function_exists('sendInteraktMessageUsingTemplates')) {
    function sendInteraktMessageUsingTemplates($data)
    {
        $decodeData = json_decode($data, true);

        // Prepare payload dynamically
        $payload = [
            "countryCode" => "+91",
            "phoneNumber" => "",
            "fullPhoneNumber" => $decodeData['phone_number'] ?? "",
            "campaignId" => "",
            "callbackData" => "some data",
            "type" => "Template",
            "template" => [
                "name" => "appointment_booking",
                "languageCode" => "en",
                "bodyValues" => [
                    strtoupper($decodeData['name'] ?? "Patient"),
                    $decodeData['doctor_name'] ?? "Doctor",
                    $decodeData['date'] ?? "Date",
                    $decodeData['time'] ?? "Time"
                ]
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.interakt.ai/v1/public/message/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . env('INTERAKT_API_KEY'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // dd($response);
        return $response;
    }
}


if (!function_exists('sendPatientUpdationForm')) {
    function sendPatientUpdationForm($country_code, $name, $contact_number, $id = 0)
    {
        $curl = curl_init();

        $data = [
            "countryCode"   => $country_code,
            "phoneNumber"   => $contact_number,
            "fullPhoneNumber" => "", // kept empty as per instructions
            "campaignId"    => "",
            "callbackData"  => "Patient Updation Form",
            "type"          => "Template",
            "template"      => [
                "name"         => "patient_update",
                "languageCode" => "en",
                "bodyValues"   => [$name],
                "buttonValues" => [
                    "1" => [(string)$id]
                ]
            ]
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.interakt.ai/v1/public/message/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Basic TE9UMkRnOF9SZjBCVVBVRHVyUFJFMEV3ZndqRWRwdHFxR0ZxWW0xTmxnRTo=',
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
