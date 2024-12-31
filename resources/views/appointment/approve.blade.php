@php
    use App\Models\Appointment;
@endphp

@if ($status == Appointment::STATUS_NOT_CONFIRMED)
    <!-- Show both Approve and Reject when the status is not confirmed -->
    <a href="javascript:void(0);" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Approve Appointment"
        id="approve-appointment-{{ $id }}" onclick="showApproveForm('{{ $id }}')">
        <i class="fas fa-check-circle text-success" aria-hidden="true"></i>
    </a>

    <a href="javascript:void(0);" class="" data-bs-toggle="tooltip" data-bs-original-title="Reject Appointment"
        id="reject-appointment-{{ $id }}" onclick="rejectAppointment('{{ $id }}')">
        <i class="fas fa-times-circle text-danger" aria-hidden="true"></i>
    </a>
@endif

@if ($status == Appointment::STATUS_CANCELLED)
    <!-- Show only Reject when the status is confirmed -->
    <a href="javascript:void(0);" class="" data-bs-toggle="tooltip" data-bs-original-title="Reject Appointment"
        id="reject-appointment-{{ $id }}" onclick="rejectAppointment('{{ $id }}')">
        <i class="fas fa-times-circle text-danger" aria-hidden="true"></i>
    </a>
@endif

@if ($status == Appointment::STATUS_CONFIRMED)
    <!-- Show only Approve when the status is cancelled -->
    <a href="javascript:void(0);" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Approve Appointment"
        id="approve-appointment-{{ $id }}" onclick="showApproveForm('{{ $id }}')">
        <i class="fas fa-check-circle text-success" aria-hidden="true"></i>
    </a>
@endif
