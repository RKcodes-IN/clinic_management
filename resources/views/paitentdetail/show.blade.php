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
            padding: 20px;
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
    </style>

    <div class="container card p-4 shadow-lg">
        <!-- Profile Header Section -->
        <div class="profile-header">
            <img src="{{ $paitent->image ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png' }}"
                alt="Profile Picture">
            <div>
                <h3 class="mb-0">{{ $paitent->name ?? 'Unknown' }}</h3>
                <p class="text-muted">Patient ID: {{ $paitent->id }}</p>
                <!-- Optional button to edit profile -->
                {{-- <button class="btn btn-primary btn-sm">Edit Profile</button> --}}
            </div>
        </div>

        <!-- Profile Information Section -->
        <div class="profile-info-card shadow-sm">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date of Birth:</strong> {{ $paitent->date_of_birth ?? 'Not available' }}</p>
                    <p><strong>Age:</strong> {{ $healthEvalution->age ?? 'N/A' }}</p>
                    <p><strong>Gender:</strong> {{ $paitent->gender ?? 'Not specified' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Contact:</strong> {{ $paitent->phone_number ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $paitent->address ?? 'No address provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Appointment History Section -->
        <div class="appointment-section">
            <h4 class="mb-4">Appointment History</h4>
            <table class="table table-hover table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Appointment Date</th>
                        <th>Doctor's Name</th>
                        <th>Reason</th>
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
            <h4 class="mb-4">Health Evalution</h4>
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


        <div class="healthevalution-section">
            <h4 class="mb-4">Previous Reports</h4>
            <table class="table table-hover table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Report Type</th>
                        <th>File</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($investigationReport as $report)
                        @if ($report)
                            <tr>
                                <!-- Report Date -->
                                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('F j, Y') }}</td>

                                <!-- Report Type -->
                                <td>{{ $report->reportType->name }}</td>

                                <!-- View File Button -->
                                <td>
                                    <a href="{{ asset('storage/' . $report->report_url) }}" target="_blank"
                                        class="btn btn-primary btn-sm">
                                        View File
                                    </a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5">Previous reports not found</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
@endsection
