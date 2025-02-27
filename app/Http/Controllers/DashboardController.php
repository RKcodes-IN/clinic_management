<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardStats()
    {
        $appointmentCounts = appointmentCounts();
        $patientCount = patientGenderCounts();
        $invoiceCount = invoiceCount();
        $invoiceAmountData = invoiceAmount();
        // Combine the results into a single response array
        $data = [
            'appointmentCounts' => $appointmentCounts,
            'patientCounts' => $patientCount,
            'invoiceCounts' => $invoiceCount,
            'invoiceAmount' => $invoiceAmountData
        ];

        return response()->json($data);
    }
}
