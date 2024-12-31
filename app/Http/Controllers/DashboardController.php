<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardStats()
    {
        $appointmentCounts = appointmentCounts();

        return response()->json($appointmentCounts);
    }
}
