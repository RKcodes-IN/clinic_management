@extends('layouts.user_type.auth')

@section('content')
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-header img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-right: 20px;
            border: 4px solid #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .badge-status {
            padding: 0.5em 1em;
            font-size: 0.85em;
            border-radius: 5px;
        }

        .profile-info-card {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .appointment-section, .health-evaluation-section {
            margin-top: 40px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
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
            border-radius: 5px;
        }

        h4.mb-4 {
            display: flex;
            align-items: center;
        }

        h4.mb-4 i {
            margin-right: 10px;
            color: #007bff;
        }

        .btn-primary {
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>

    <div class="container card p-3 shadow-lg">
        <!-- Profile Header Section -->
        <div class="profile-header">
            <img src="{{ $paitent->image ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png' }}" alt="Profile Picture">
            <div class="row w-100">
                <div class="col-6">
                    <h3 class="mb-0">{{ $paitent->name ?? 'Unknown' }}</h3>
                    <p class="text-muted">Patient ID: {{ $paitent->id ?? '' }}</p>
                </div>
                <div class="col-6">
                    <a href="#previousreport" class="btn btn-primary">Go to Reports</a>
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#viewAllReportsModal">View All Reports ({{ count($investigationReport) }})</button>
                    <a href="#casesheets-section" class="btn btn-primary">Go to Case Sheets</a>
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#viewAllCaseSheetsModal">View All Case Sheets ({{ count($casesheets) }})</button>
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
                </div>
                <div class="col-md-6">
                    <p><strong>Contact:</strong> {{ $paitent->phone_number ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $paitent->address ?? 'No address provided' }}</p>
                    <p><strong>Total Visits:</strong> {{ $appontmentCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Appointment History Section -->
        <div class="appointment-section">
            <h4 class="mb-4"><i class="fas fa-calendar-alt"></i> Appointment History</h4>
            <table class="table table-hover table-bordered table-striped">
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
                                <span class="badge {{ $appontment->status == 1 ? 'bg-warning' : ($appontment->status == 2 ? 'bg-success' : ($appontment->status == 3 ? 'bg-info' : 'bg-danger')) }} badge-status">
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

        <!-- Health Evaluation Section -->
        <div class="health-evaluation-section">
            <h4 class="mb-4"><i class="fas fa-heartbeat"></i> Health Evaluation</h4>
            <table class="table table-hover table-bordered table-striped">
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
                                <td>{{ $healthEvalution->weight ?? '' }}</td>
                                <td>{{ $healthEvalution->height ?? '' }}</td>
                                <td>{{ $healthEvalution->allergic_to_drugs ?? '' }}</td>
                                <td>{{ $healthEvalution->food_allergies ?? '' }}</td>
                                <td>
                                    <a href="{{ route('healthevalution.show', ['id' => $healthEvalution->id]) }}">
                                        <i class="fas fa-eye action-icon"></i>
                                    </a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="6">Invalid health evaluation record.</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Case Sheets Section -->
        <div class="health-evaluation-section" id="casesheets-section">
            <h4 class="mb-4"><i class="fas fa-file-medical"></i> Case Sheets</h4>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#viewAllCaseSheetsModal">View All</button>
            <table class="table table-hover table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Report Types</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($casesheets as $casesheet)
                        @if ($casesheet)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($casesheet->report_date)->format('F j, Y') }}</td>
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
                                            @foreach ($casesheet->reportTypeValues as $value)
                                                <tr class="{{ $value->out_of_range == 'yes' ? 'text-danger' : '' }}">
                                                    <td>{{ $value->reportType->name ?? 'N/A' }}</td>
                                                    <td>{{ $value->value ?? '' }}</td>
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
                                    <a href="{{ asset('storage/app/public/' . $casesheet->report_url) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
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

        <!-- Previous Reports Section -->
        <div class="health-evaluation-section" id="previousreport">
            <h4 class="mb-4"><i class="fas fa-folder-open"></i> Previous Reports</h4>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#viewAllReportsModal">View All</button>
            <table class="table table-hover table-bordered table-striped">
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
                                                    <td>{{ $value->value ?? '' }}</td>
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
                                    <a href="{{ asset('storage/app/public/' . $report->report_url) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
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

        <!-- Pharmacy Prescriptions Section -->
        <div class="health-evaluation-section">
            <h4 class="mb-4"><i class="fas fa-prescription-bottle-alt"></i> Pharmacy Prescriptions</h4>
            <table class="table table-hover table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacyPrescriptions as $prescriptions)
                        @if ($prescriptions)
                            <tr>
                                <td>{{ $prescriptions->date ?? '' }}</td>
                                <td>{{ $prescriptions->item->name ?? '' }}</td>
                                <td>{{ $prescriptions->quantity ?? '' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="3">Invalid prescription record.</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Lab Tests Section -->
        <div class="health-evaluation-section">
            <h4 class="mb-4"><i class="fas fa-vial"></i> Lab Tests</h4>
            <table class="table table-hover table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labPrescriptions as $labprescriptions)
                        @if ($labprescriptions)
                            <tr>
                                <td>{{ $labprescriptions->date }}</td>
                                <td>{{ $labprescriptions->item->name }}</td>
                                <td>{{ $labprescriptions->quantity }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="3">Invalid lab test record.</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reports Modal -->
    <div class="modal fade" id="viewAllReportsModal" tabindex="-1" aria-labelledby="viewAllReportsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAllReportsModalLabel">View Report <span id="reportTitle"></span></h5>
                    <div class="float-right">
                        <button id="previousReportTop" class="btn btn-secondary">Previous</button>
                        <button id="nextReportTop" class="btn btn-secondary">Next</button>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" src="" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <button id="previousReport" class="btn btn-secondary">Previous</button>
                    <button id="nextReport" class="btn btn-secondary">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Sheets Modal -->
    <div class="modal fade" id="viewAllCaseSheetsModal" tabindex="-1" aria-labelledby="viewAllCaseSheetsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAllCaseSheetsModalLabel">View Case Sheet <span id="caseSheetTitle"></span></h5>
                    <div class="float-right">
                        <button id="previousCaseSheetTop" class="btn btn-secondary">Previous</button>
                        <button id="nextCaseSheetTop" class="btn btn-secondary">Next</button>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewerCaseSheets" src="" width="100%" height="500px"></iframe>
                </div>
                <div class="modal-footer">
                    <button id="previousCaseSheet" class="btn btn-secondary">Previous</button>
                    <button id="nextCaseSheet" class="btn btn-secondary">Next</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reports
            const reports = @json($investigationReport);
            const pdfViewerReports = document.getElementById('pdfViewer');
            let currentReportIndex = 0;

            function loadReport(index) {
                if (index >= 0 && index < reports.length) {
                    const reportUrl = reports[index].report_url;
                    pdfViewerReports.src = `https://indiaseva.net/storage/app/public/${reportUrl}`;
                    currentReportIndex = index;
                    document.getElementById('reportTitle').textContent = ` - Report ${index + 1} of ${reports.length}`;
                }
            }

            document.getElementById('viewAllReportsModal').addEventListener('shown.bs.modal', function() {
                loadReport(currentReportIndex);
            });

            document.getElementById('previousReport').addEventListener('click', function() {
                if (currentReportIndex > 0) loadReport(currentReportIndex - 1);
            });

            document.getElementById('nextReport').addEventListener('click', function() {
                if (currentReportIndex < reports.length - 1) loadReport(currentReportIndex + 1);
            });

            document.getElementById('previousReportTop').addEventListener('click', function() {
                if (currentReportIndex > 0) loadReport(currentReportIndex - 1);
            });

            document.getElementById('nextReportTop').addEventListener('click', function() {
                if (currentReportIndex < reports.length - 1) loadReport(currentReportIndex + 1);
            });

            // Case Sheets
            const caseSheets = @json($casesheets);
            const pdfViewerCaseSheets = document.getElementById('pdfViewerCaseSheets');
            let currentCaseSheetIndex = 0;

            function loadCaseSheet(index) {
                if (index >= 0 && index < caseSheets.length) {
                    const reportUrl = caseSheets[index].report_url;
                    pdfViewerCaseSheets.src = `https://indiaseva.net/storage/app/public/${reportUrl}`;
                    currentCaseSheetIndex = index;
                    document.getElementById('caseSheetTitle').textContent = ` - Case Sheet ${index + 1} of ${caseSheets.length}`;
                }
            }

            document.getElementById('viewAllCaseSheetsModal').addEventListener('shown.bs.modal', function() {
                loadCaseSheet(currentCaseSheetIndex);
            });

            document.getElementById('previousCaseSheet').addEventListener('click', function() {
                if (currentCaseSheetIndex > 0) loadCaseSheet(currentCaseSheetIndex - 1);
            });

            document.getElementById('nextCaseSheet').addEventListener('click', function() {
                if (currentCaseSheetIndex < caseSheets.length - 1) loadCaseSheet(currentCaseSheetIndex + 1);
            });

            document.getElementById('previousCaseSheetTop').addEventListener('click', function() {
                if (currentCaseSheetIndex > 0) loadCaseSheet(currentCaseSheetIndex - 1);
            });

            document.getElementById('nextCaseSheetTop').addEventListener('click', function() {
                if (currentCaseSheetIndex < caseSheets.length - 1) loadCaseSheet(currentCaseSheetIndex + 1);
            });
        });
    </script>
@endsection
