@extends('layouts.user_type.auth')

@section('content')
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-right: 20px;
            border: 4px solid #007bff;
        }

        .badge-status {
            padding: 0.5em 1em;
            font-size: 0.85em;
            border-radius: 5px;
        }

        .profile-info-card {
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .appointment-section {
            margin-top: 40px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table .action-icon {
            font-size: 1.2rem;
            color: #6c757d;
            transition: color 0.3s;
        }

        .table .action-icon:hover {
            color: #007bff;
        }

        iframe {
            border: none;
        }
    </style>

    <div class="container card p-3 shadow-lg">
        <!-- Profile Header Section -->
        <div class="profile-header">
            <img src="{{ $paitent->image ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png' }}"
                alt="Profile Picture">
            <div class="row">
                <div class="col-6">
                    <h3 class="mb-0">{{ $paitent->name ?? 'Unknown' }}</h3>
                    <p class="text-muted">Patient ID: {{ $paitent->id }}</p>
                    <!-- Optional button to edit profile -->
                    {{-- <button class="btn btn-primary btn-sm">Edit Profile</button> --}}
                </div>
                <div class="col-6">

                    <a href="#previousreport" class="btn btn-primary"> Go to Reports</a>
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#viewAllModal">View
                        All Reports ({{ count($investigationReport) }})</button>
                </div>
            </div>
        </div>

        <!-- Profile Information Section -->
        <div class="profile-info-card shadow-sm">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date of Birth:</strong> {{ $paitent->date_of_birth ?? 'Not available' }}</p>
                    <p><strong>Age:</strong> {{ $paitent->age ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ $paitent->gender ?? 'Not specified' }}</p>
                    {{-- <p><strong>Total Reports:</strong> {{  }}</p> --}}

                </div>
                <div class="col-md-6">
                    <p><strong>Contact:</strong> {{ $paitent->phone_number ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $paitent->address ?? 'No address provided' }}</p>
                    <p><strong>Total Visits:</strong> {{ $appontmentCount }}</p>
                </div>
            </div>
        </div>

        <!-- Appointment History Section -->
        <div class="appointment-section" style="margin-top: -25px !important;">
            <h4 class="mb-4">Appointment History</h4>
            <table class="table table-hover table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Appointment Date</th>
                        <th>Doctor's Name</th>
                        <th>Main Complaint</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appontments as $appontment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($appontment->available_date)->format('F j, Y') }}</td>
                            <td>{{ $appontment->doctor->name ?? 'Not assigned' }}</td>
                            <td>{{ $appontment->main_complaint ?? 'No complaint provided' }}</td>
                            <td>
                                <span
                                    class="badge {{ $appontment->status == 1 ? 'bg-warning' : ($appontment->status == 2 ? 'bg-success' : ($appontment->status == 3 ? 'bg-info' : 'bg-danger')) }} badge-status">
                                    {{ strip_tags($appontment->getStatusLabel($appontment->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('appointments.show', ['id' => $appontment->id]) }}">
                                    <i class="fas fa-eye action-icon"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="healthevalution-section">
            <h4 class="mb-4">Health Evaluation</h4>
            <table class="table table-hover table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Weight</th>
                        <th>Height</th>
                        <th>Allergic To Drugs</th>
                        <th>Allergic To Food</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($healthEvalutions as $healthEvalution)
                        @if ($healthEvalution)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($healthEvalution->created_at)->format('F j, Y') }}</td>

                                <td>{{ $healthEvalution->weight }}</td>
                                <td>{{ $healthEvalution->height }}</td>
                                <td>{{ $healthEvalution->allergic_to_drugs }}</td>
                                <td>{{ $healthEvalution->food_allergies }}</td>
                                <td>
                                    <a href="{{ route('healthevalution.show', ['id' => $healthEvalution->id]) }}">
                                        <i class="fas fa-eye action-icon"></i>
                                    </a>
                                </td>

                            </tr>
                        @else
                            <tr>
                                <td colspan="5">Invalid health evaluation record.</td>
                            </tr>
                        @endif
                    @endforeach

                </tbody>

            </table>
        </div>


        <div class="healthevalution-section" id="previousreport">
            <h4 class="mb-4">Previous Reports</h4>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#viewAllModal">View All</button>


            <table class="table table-hover table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Report Types</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($investigationReport as $report)
                        @if ($report)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('F j, Y') }}</td>
                                <td>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Value</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($report->reportTypeValues as $value)
                                                <tr class="{{ $value->out_of_range == 'yes' ? 'text-danger' : '' }}">
                                                    <td>{{ $value->reportType->name ?? 'N/A' }}</td>
                                                    <td>{{ $value->value }}</td>
                                                    <td>
                                                        @if ($value->out_of_range == 'yes')
                                                            <span class="text-danger">Out of Range</span>
                                                        @else
                                                            Normal
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <a href="{{ asset('storage/app/public/' . $report->report_url) }}" target="_blank"
                                        class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="3">Previous reports not found</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>



        </div>

    </div>


    <div class="modal fade" id="viewAllModal" tabindex="-1" aria-labelledby="viewAllModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAllModalLabel">View Report</h5>


                    <div class="float-right">
                        <button id="previousReportTop" class="btn btn-secondary">Previous</button>
                        <button id="nextReportTop" class="btn btn-secondary">Next</button>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                            aria-label="Close">X</button>

                    </div>
                </div>
                <div class="modal-body">
                    <!-- PDF Viewer -->
                    <iframe id="pdfViewer" src="" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <button id="previousReport" class="btn btn-secondary">Previous</button>
                    <button id="nextReport" class="btn btn-secondary">Next</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pass reports data from Laravel to JavaScript
            const reports = @json($investigationReport);
            const pdfViewer = document.getElementById('pdfViewer');
            let currentIndex = 0;

            // Function to load a report into the iframe
            function loadReport(index) {
                if (index >= 0 && index < reports.length) {
                    const reportUrl = reports[index].report_url; // Get the report URL
                    pdfViewer.src =
                        `https://indiaseva.net/storage/app/public/${reportUrl}`; // Set the iframe source
                    currentIndex = index;
                }
            }

            // Event Listener: Load the first report when modal is shown
            document.getElementById('viewAllModal').addEventListener('shown.bs.modal', function() {
                loadReport(currentIndex);
            });

            // Event Listener: Navigate to the previous report
            document.getElementById('previousReport').addEventListener('click', function() {
                if (currentIndex > 0) {
                    loadReport(currentIndex - 1);
                }
            });

            // Event Listener: Navigate to the next report
            document.getElementById('nextReport').addEventListener('click', function() {
                if (currentIndex < reports.length - 1) {
                    loadReport(currentIndex + 1);
                }
            });

            document.getElementById('previousReportTop').addEventListener('click', function() {
                if (currentIndex > 0) {
                    loadReport(currentIndex - 1);
                }
            });

            // Event Listener: Navigate to the next report
            document.getElementById('nextReportTop').addEventListener('click', function() {
                if (currentIndex < reports.length - 1) {
                    loadReport(currentIndex + 1);
                }
            });

        });
    </script>
@endsection
