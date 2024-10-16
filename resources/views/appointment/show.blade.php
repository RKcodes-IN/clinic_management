@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">Appointment Details</h2>
        </div>
        <div class="card-body">
            <h5 class="card-title text-primary">
                Appointment with
                @if ($appointment->doctor)
                    Dr. {{ $appointment->doctor->name }}
                @else
                    <span class="text-muted">(Unassigned)</span>
                @endif
            </h5>

            <ul class="list-group list-group-flush mt-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Patient Name:</strong>
                    <span>{{ $appointment->patient->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Email:</strong>
                    <span>{{ $appointment->email }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Phone Number:</strong>
                    <span>{{ $appointment->phone_number }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Address:</strong>
                    <span>{{ $appointment->address }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Main Complaint:</strong>
                    <span>{{ $appointment->main_complaint }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Previous Report Available:</strong>
                    <span>{{ $appointment->is_previous_report_available ? 'Yes' : 'No' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Available Date:</strong>
                    <span>{{ \Carbon\Carbon::parse($appointment->available_date)->format('F j, Y') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Time Slot:</strong>
                    <span>{{ \Carbon\Carbon::parse($appointment->time_from)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->time_to)->format('g:i A') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Status:</strong>
                    <span>
                        @if($appointment->status == 1)
                            <span class="badge bg-warning">Pending</span>
                        @elseif($appointment->status == 2)
                            <span class="badge bg-success">Confirmed</span>
                        @elseif($appointment->status == 3)
                            <span class="badge bg-info">Completed</span>
                        @elseif($appointment->status == 4)
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </span>
                </li>
                @if ($appointment->message)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Message:</strong>
                        <span>{{ $appointment->message }}</span>
                    </li>
                @endif
            </ul>
        </div>
        <div class="card-footer text-center py-3">
            <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-lg">Back to Appointments</a>
        </div>
    </div>
</div>
@endsection
