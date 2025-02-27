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
    @if (Auth::user()->hasRole('admin'))
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
                                                    <th>Expenses</th>
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
                                                    <td>20</td> <!-- Replace with dynamic value -->
                                                </tr>
                                                <!-- Today's Counts -->
                                                <tr>
                                                    <td><strong>Deleted</strong></td>
                                                    <td>10</td> <!-- Replace with dynamic value -->
                                                    <td>5</td> <!-- Replace with dynamic value -->
                                                    <td>5</td> <!-- Replace with dynamic value -->
                                                    <td>3</td> <!-- Replace with dynamic value -->
                                                    <td>3</td> <!-- Replace with dynamic value -->
                                                    <td>3</td> <!-- Replace with dynamic value -->
                                                    <td>3</td> <!-- Replace with dynamic value -->
                                                </tr>
                                                <tr>
                                                    <td><strong>Value</strong></td>
                                                    <td>10</td> <!-- Replace with dynamic value -->
                                                    <td>5</td> <!-- Replace with dynamic value -->
                                                    <td>3</td> <!-- Replace with dynamic value -->
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
                <!-- Appointments counts -->
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Appointments</p>
                            <div class="">

                                <div class="d-flex justify-content-center">
                                    <div id="loader" class="loader spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <table class="table table-sm table-striped" id="data-table" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Total</th>
                                            <th>New</th>
                                            <th>Review</th>
                                            <th>Re-Visit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Overall</strong></td>
                                            <td id="overall-total"></td>
                                            <td id="overall-new"></td>
                                            <td id="overall-review"></td>
                                            <td id="overall-second"></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Month</strong></td>
                                            <td id="month-total"></td>
                                            <td id="month-new"></td>
                                            <td id="month-review"></td>
                                            <td id="month-second"></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Today</strong></td>
                                            <td id="today-total"></td>
                                            <td id="today-new"></td>
                                            <td id="today-review"></td>
                                            <td id="today-second"></td>
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

                                            <div class="d-flex justify-content-center">
                                                <div id="loader" class="spinner-border text-primary loader"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                            <table class="table table-sm table-striped loading-show" style="display: none;">
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
                                                        <td id="patient-overall"></td> <!-- Replace with dynamic value -->
                                                        <td id="patient-male-overall"></td>
                                                        <!-- Replace with dynamic value -->
                                                        <td id="patient-female-overall"></td>
                                                        <!-- Replace with dynamic value -->
                                                    </tr>
                                                    <!-- Current Month Counts -->
                                                    <tr>
                                                        <td><strong>Month</strong></td>
                                                        <td id="patient-month-overall"></td>
                                                        <!-- Replace with dynamic value -->
                                                        <td id="patient-month-male"></td>
                                                        <!-- Replace with dynamic value -->
                                                        <td id="patient-month-female"></td>
                                                        <!-- Replace with dynamic value -->
                                                    </tr>
                                                    <!-- Today's Counts -->
                                                    <tr>
                                                        <td><strong>Today</strong></td>
                                                        <td id="patient-today-overall"></td>
                                                        <!-- Replace with dynamic value -->
                                                        <td id="patient-today-male"></td>
                                                        <!-- Replace with dynamic value -->
                                                        <td id="patient-today-female"></td>
                                                        <!-- Replace with dynamic value -->
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
                                            <div class="d-flex justify-content-center">
                                                <div id="loader" class="spinner-border text-primary loader"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
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
                                                        <td id="invoice-overall-total">100</td>
                                                        <td id="invoice-overall-paid">30</td>
                                                        <td id="invoice-overall-pending">50</td>
                                                    </tr>
                                                    <!-- Current Month Counts -->
                                                    <tr>
                                                        <td><strong>Month</strong></td>
                                                        <td id="invoice-month-total">40</td>
                                                        <td id="invoice-month-paid">15</td>
                                                        <td id="invoice-month-pending">20</td>
                                                    </tr>
                                                    <!-- Today's Counts -->
                                                    <tr>
                                                        <td><strong>Today</strong></td>
                                                        <td id="invoice-today-total">10</td>
                                                        <td id="invoice-today-paid">5</td>
                                                        <td id="invoice-today-pending">3</td>
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
                                                    <!-- Overall Amounts -->
                                                    <tr>
                                                        <td><strong>Overall</strong></td>
                                                        <td id="invoice-overall-totalamt">0</td>
                                                        <td id="invoice-overall-paidamt">0</td>
                                                        <td id="invoice-overall-pendingamt">0</td>
                                                    </tr>
                                                    <!-- This Month Amounts -->
                                                    <tr>
                                                        <td><strong>Month</strong></td>
                                                        <td id="invoice-month-totalamt">0</td>
                                                        <td id="invoice-month-paidamt">0</td>
                                                        <td id="invoice-month-pendingamt">0</td>
                                                    </tr>
                                                    <!-- Today's Amounts -->
                                                    <tr>
                                                        <td><strong>Today</strong></td>
                                                        <td id="invoice-today-totalamt">0</td>
                                                        <td id="invoice-today-paidamt">0</td>
                                                        <td id="invoice-today-pendingamt">0</td>
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

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pharmacy</p>
                            <div class="">

                                <div class="d-flex justify-content-center">
                                    <div id="loader" class="loader spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                <table class="table table-sm table-striped" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Total Items</th>
                                            <th>Below Alert</th>
                                            <th>Below Ideal</th>
                                            <th>Add Item</th>
                                            <th>Issue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Overall</strong></td>
                                            <td >0</td>
                                            <td >0</td>
                                            <td>0</td>
                                            <td >0</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Month</strong></td>
                                            <td>0</td>
                                            <td >0</td>
                                            <td>0</td>
                                            <td >0</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Today</strong></td>
                                            <td >0</td>
                                            <td >0</td>
                                            <td >0</td>
                                            <td >0</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif
@endsection
@push('dashboard')
    <script>
        $(document).ready(function() {
            // Show loader and hide table initially
            $(".loader").show();
            $("#data-table").hide();

            $.ajax({
                type: "GET",
                url: "{{ route('dashboard.counts') }}",
                dataType: "json",
                success: function(response) {
                    console.log(response); // Debug the response if needed

                    // Appointment Counts (existing code)
                    $("#overall-total").text(response.appointmentCounts.totalAppointments || 0);
                    $("#overall-new").text(response.appointmentCounts.totalAppointmentsNew || 0);
                    $("#overall-review").text(response.appointmentCounts.totalAppointmentsReview || 0);
                    $("#overall-second").text(response.appointmentCounts.patientsWithTwoOrMoreTotal ||
                        0);
                    $("#month-total").text(response.appointmentCounts.thisMonthAppointments || 0);
                    $("#month-new").text(response.appointmentCounts.thisMonthAppointmentsNew || 0);
                    $("#month-review").text(response.appointmentCounts.thisMonthAppointmentsReview ||
                        0);
                    $("#month-second").text(response.appointmentCounts.patientsWithTwoOrMoreThisMonth ||
                        0);
                    $("#today-total").text(response.appointmentCounts.todayAppointments || 0);
                    $("#today-new").text(response.appointmentCounts.todayAppointmentsNew || 0);
                    $("#today-review").text(response.appointmentCounts.todayAppointmentsReview || 0);
                    $("#today-second").text(response.appointmentCounts.patientsWithTwoOrMoreToday || 0);

                    // Patient Counts (existing code)
                    $("#patient-overall").text(response.patientCounts.total.all || 0);
                    $("#patient-male-overall").text(response.patientCounts.total.male || 0);
                    $("#patient-female-overall").text(response.patientCounts.total.female || 0);
                    $("#patient-month-overall").text(response.patientCounts.thisMonth.all || 0);
                    $("#patient-month-male").text(response.patientCounts.thisMonth.male || 0);
                    $("#patient-month-female").text(response.patientCounts.thisMonth.female || 0);
                    $("#patient-today-overall").text(response.patientCounts.today.all || 0);
                    $("#patient-today-male").text(response.patientCounts.today.male || 0);
                    $("#patient-today-female").text(response.patientCounts.today.female || 0);

                    // Invoice Counts (new code)
                    // Overall
                    $("#invoice-overall-total").text(response.invoiceCounts.total.all || 0);
                    $("#invoice-overall-paid").text(response.invoiceCounts.total.paid || 0);
                    $("#invoice-overall-pending").text((response.invoiceCounts.total.all || 0) - (
                        response.invoiceCounts.total.paid || 0));
                    // This Month
                    $("#invoice-month-total").text(response.invoiceCounts.thisMonth.all || 0);
                    $("#invoice-month-paid").text(response.invoiceCounts.thisMonth.paid || 0);
                    $("#invoice-month-pending").text((response.invoiceCounts.thisMonth.all || 0) - (
                        response.invoiceCounts.thisMonth.paid || 0));
                    // Today
                    $("#invoice-today-total").text(response.invoiceCounts.today.all || 0);
                    $("#invoice-today-paid").text(response.invoiceCounts.today.paid || 0);
                    $("#invoice-today-pending").text((response.invoiceCounts.today.all || 0) - (response
                        .invoiceCounts.today.paid || 0));


                    $("#invoice-overall-totalamt").text(response.invoiceAmount.total.all || 0);
                    $("#invoice-overall-paidamt").text(response.invoiceAmount.total.paid || 0);
                    $("#invoice-overall-pendingamt").text(response.invoiceAmount.total.pending || 0);
                    // This Month
                    $("#invoice-month-totalamt").text(response.invoiceAmount.thisMonth.all || 0);
                    $("#invoice-month-paidamt").text(response.invoiceAmount.thisMonth.paid || 0);
                    $("#invoice-month-pendingamt").text(response.invoiceAmount.thisMonth.pending || 0);
                    // Today
                    $("#invoice-today-totalamt").text(response.invoiceAmount.today.all || 0);
                    $("#invoice-today-paidamt").text(response.invoiceAmount.today.paid || 0);
                    $("#invoice-today-pendingamt").text(response.invoiceAmount.today.pending || 0);

                    // Hide loader and show the table
                    $(".loader").hide();
                    $("#data-table").show();
                    $(".loading-show").show();
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: " + error);
                    $("#loader").html("<p>Failed to load data. Please try again.</p>");
                }
            });
        });
    </script>
@endpush
