
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

        // Helper function to get patient counts based on filters
        $getPatientCount = function ($gender = null, $dateFilter = null) use ($currentMonth, $currentYear, $today) {
            $query = Invoice::query();

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

            ],
            'thisMonth' => [
                'all' => $thisMonthPatients,
                'male' => $thisMonthMalePatients,
                'female' => $thisMonthFemalePatients,

            ],
            'today' => [
                'all' => $todayPatients,
                'male' => $todayMalePatients,
                'female' => $todayFemalePatients,

            ]
        ];

        return $data;
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
                        $decodeData['name'] ?? "Patient",
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
}
