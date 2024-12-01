@extends('layouts.user_type.auth')

@section('content')
    <style>
        .rotate-icon {
            transition: transform 0.3s;
        }

        .accordion-button:not(.collapsed) .rotate-icon {
            transform: rotate(180deg);
        }


        .table th,
        .table td {
            padding: 0.2rem !important;
            /* Reduce padding between columns */
            font-size: 0.9rem;
            /* Adjust font size if needed */
        }

        .table thead th {
            text-align: center;
            /* Center align headers for better aesthetics */
        }

        .table tbody td {
            text-align: center;
            /* Center align data cells for uniformity */
        }

        .card-body {
            padding: 1rem;
            /* Ensure there's consistent padding inside cards */
        }
    </style>

    @php
        use App\Models\Appointment;
        use Carbon\Carbon;

        // Total appointments
        $totalAppointments = Appointment::count();

        // This month's appointments
$thisMonthAppointments = Appointment::whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();

// Today's appointments
        $todayAppointments = Appointment::whereDate('created_at', Carbon::today())->count();

        // Total appointments
        $totalAppointments = Appointment::count();

        // This month's appointments
$thisMonthAppointments = Appointment::whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();

// Today's appointments
        $todayAppointments = Appointment::whereDate('created_at', Carbon::today())->count();

        // Total appointments
        $totalAppointmentsNew = Appointment::where('status', Appointment::STATUS_NOT_CONFIRMED)->count();

        // This month's appointments
$thisMonthAppointmentsNew = Appointment::whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->Where('status', Appointment::STATUS_NOT_CONFIRMED)
    ->count();

// Today's appointments
        $todayAppointmentsNew = Appointment::whereDate('created_at', Carbon::today())
            ->where('status', Appointment::STATUS_NOT_CONFIRMED)

            ->count();

        $totalAppointmentsReview = Appointment::where('status', Appointment::STATUS_CONFIRMED)->count();

        // This month's appointments
$thisMonthAppointmentsReview = Appointment::whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->Where('status', Appointment::STATUS_CONFIRMED)
    ->count();

// Today's appointments
        $todayAppointmentsReview = Appointment::whereDate('created_at', Carbon::today())
            ->where('status', Appointment::STATUS_CONFIRMED)

            ->count();

        $patientsWithTwoOrMoreToday = Appointment::select('patient_id')
            ->whereDate('created_at', Carbon::today())
            ->groupBy('patient_id')
            ->havingRaw('COUNT(*) >= 2')
            ->count();

        $patientsWithTwoOrMoreThisMonth = Appointment::select('patient_id')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('patient_id')
            ->havingRaw('COUNT(*) >= 2')
            ->count();

        $patientsWithTwoOrMoreTotal = Appointment::select('patient_id')
            ->groupBy('patient_id')
            ->havingRaw('COUNT(*) >= 2')
            ->count();

    @endphp

<h4 class="d-flex">Select Date:
    <div class="col-3 ml-3">
      <input type="date" name="" value="{{ date('Y-m-d') }}" class="form-control" id="">
    </div>
  </h4>
      <div class="row mt-3">

        <div class="col-xl-12 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Operations</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Appointments</th>
                                                <th>Patients</th>
                                                <th>Invoice </th>
                                                <th>Prescription No </th>
                                                <th>Inventory </th>
                                                <th>Lab </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Created</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Updated</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Deleted</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



                <div class="card-body">
                    <div class="row">
                        <!-- Appointments -->
                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Appointments</p>
                                    <div class="">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Total</th>
                                                    <th>New</th>
                                                    <th>Review</th>
                                                    <th>Second</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Overall</strong></td>
                                                    <td>{{ $totalAppointments }}</td>
                                                    <td>{{ $totalAppointmentsNew }}</td>
                                                    <td>{{ $totalAppointmentsReview }}</td>
                                                    <td>{{ $patientsWithTwoOrMoreTotal }}</td>

                                                </tr>
                                                <tr>
                                                    <td><strong>Month</strong></td>
                                                    <td>{{ $thisMonthAppointments }}</td>
                                                    <td>{{ $thisMonthAppointmentsNew }}</td>
                                                    <td>{{ $thisMonthAppointmentsReview }}</td>
                                                    <td>{{ $patientsWithTwoOrMoreThisMonth }}</td>

                                                </tr>
                                                <tr>
                                                    <td><strong>Today</strong></td>
                                                    <td>{{ $todayAppointments }}</td>
                                                    <td>{{ $todayAppointmentsNew }}</td>
                                                    <td>{{ $todayAppointmentsReview }}</td>

                                                    <td>{{ $patientsWithTwoOrMoreTotal }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Visits -->
                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Visits</p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Total</th>
                                                    <th>New</th>
                                                    <th>Review</th>
                                                    <th>Second</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Overall</strong></td>
                                                    <td>100</td>
                                                    <td>30</td>
                                                    <td>50</td>
                                                    <td>20</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Month</strong></td>
                                                    <td>40</td>
                                                    <td>15</td>
                                                    <td>20</td>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Today</strong></td>
                                                    <td>10</td>
                                                    <td>5</td>
                                                    <td>3</td>
                                                    <td>2</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Patients</p>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Category</th>
                                                                <th>Total</th>
                                                                <th>Male</th>
                                                                <th>Female</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Overall Counts -->
                                                            <tr>
                                                                <td><strong>Overall</strong></td>
                                                                <td>100</td> <!-- Replace with dynamic value -->
                                                                <td>30</td> <!-- Replace with dynamic value -->
                                                                <td>50</td> <!-- Replace with dynamic value -->
                                                            </tr>
                                                            <!-- Current Month Counts -->
                                                            <tr>
                                                                <td><strong>Month</strong></td>
                                                                <td>40</td> <!-- Replace with dynamic value -->
                                                                <td>15</td> <!-- Replace with dynamic value -->
                                                                <td>20</td> <!-- Replace with dynamic value -->
                                                            </tr>
                                                            <!-- Today's Counts -->
                                                            <tr>
                                                                <td><strong>Today</strong></td>
                                                                <td>10</td> <!-- Replace with dynamic value -->
                                                                <td>5</td> <!-- Replace with dynamic value -->
                                                                <td>3</td> <!-- Replace with dynamic value -->
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



    <div class="row mt-3">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Invoice</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Invoice Amount</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Expenses</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Pending </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Lab</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Pending </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Prescription No</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Prescription Value</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Pending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Inventory</p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Purchased</th>
                                                <th>Sell</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Overall Counts -->
                                            <tr>
                                                <td><strong>Overall</strong></td>
                                                <td>100</td> <!-- Replace with dynamic value -->
                                                <td>30</td> <!-- Replace with dynamic value -->
                                                <td>50</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Current Month Counts -->
                                            <tr>
                                                <td><strong>Month</strong></td>
                                                <td>40</td> <!-- Replace with dynamic value -->
                                                <td>15</td> <!-- Replace with dynamic value -->
                                                <td>20</td> <!-- Replace with dynamic value -->
                                            </tr>
                                            <!-- Today's Counts -->
                                            <tr>
                                                <td><strong>Today</strong></td>
                                                <td>10</td> <!-- Replace with dynamic value -->
                                                <td>5</td> <!-- Replace with dynamic value -->
                                                <td>3</td> <!-- Replace with dynamic value -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@push('dashboard')
    <script>
        window.onload = function() {
            var ctx = document.getElementById("chart-bars").getContext("2d");

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Sales",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#fff",
                        data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                        maxBarThickness: 6
                    }, ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                            },
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 500,
                                beginAtZero: true,
                                padding: 15,
                                font: {
                                    size: 14,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                color: "#fff"
                            },
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false
                            },
                            ticks: {
                                display: false
                            },
                        },
                    },
                },
            });


            var ctx2 = document.getElementById("chart-line").getContext("2d");

            var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
            gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
            gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

            var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

            gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
            gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
            gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

            new Chart(ctx2, {
                type: "line",
                data: {
                    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                            label: "Mobile apps",
                            tension: 0.4,
                            borderWidth: 0,
                            pointRadius: 0,
                            borderColor: "#0c9acb",
                            borderWidth: 3,
                            backgroundColor: gradientStroke1,
                            fill: true,
                            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                            maxBarThickness: 6

                        },
                        {
                            label: "Websites",
                            tension: 0.4,
                            borderWidth: 0,
                            pointRadius: 0,
                            borderColor: "#3A416F",
                            borderWidth: 3,
                            backgroundColor: gradientStroke2,
                            fill: true,
                            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
                            maxBarThickness: 6
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#b2b9bf',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#b2b9bf',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                },
            });
        }
    </script>
@endpush
