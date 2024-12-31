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
            <!-- Appointments counts -->
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Appointments</p>
                        <div class="">

                            <div class="d-flex justify-content-center">
                                <div id="loader" class="spinner-grow text-primary" role="status">
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
                                        <th>Second</th>
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
            $(document).ready(function() {
                // Show loader and hide table initially
                $("#loader").show();
                $("#data-table").hide();

                $.ajax({
                    type: "GET", // Use GET method
                    url: "{{ route('dashboard.counts') }}", // Use Laravel route helper to generate the URL
                    dataType: "json", // Set dataType to JSON
                    success: function(response) {
                        console.log(response); // Debug the response if needed

                        // Populate the table with the response data
                        $("#overall-total").text(response.totalAppointments || 0);
                        $("#overall-new").text(response.totalAppointmentsNew || 0);
                        $("#overall-review").text(response.totalAppointmentsReview || 0);
                        $("#overall-second").text(response.patientsWithTwoOrMoreTotal || 0);

                        $("#month-total").text(response.thisMonthAppointments || 0);
                        $("#month-new").text(response.thisMonthAppointmentsNew || 0);
                        $("#month-review").text(response.thisMonthAppointmentsReview || 0);
                        $("#month-second").text(response.patientsWithTwoOrMoreThisMonth || 0);

                        $("#today-total").text(response.todayAppointments || 0);
                        $("#today-new").text(response.todayAppointmentsNew || 0);
                        $("#today-review").text(response.todayAppointmentsReview || 0);
                        $("#today-second").text(response.patientsWithTwoOrMoreToday || 0);

                        // Hide loader and show the table
                        $("#loader").hide();
                        $("#data-table").show();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error occurred: " + error); // Handle error if any
                        $("#loader").html(
                            "<p>Failed to load data. Please try again.</p>"); // Show error message
                    }
                });
            });
        </script>
    @endpush
