@can('read appointment')
    <a href="{{ route('appointments.show', ['id' => $id]) }}" class="" data-bs-toggle="tooltip"
        data-bs-original-title="view appointment">
        <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
    </a>
@endcan

@can('edit appointment')
    <a title="Edit Appointment" href="{{ route('appointments.edit', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
        data-bs-original-title="Edit user">
        <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
    </a>
@endcan


@can('edit appointment')
    <a title="Create Pharmacy Prescriptions"
        href="{{ route('prescription.create', ['appointmentId' => $id, 'patientId' => '']) }}" class="mx-3"
        data-bs-toggle="tooltip" data-bs-original-title="Add Prescriptions">
        <i class="fa-solid fa-clipboard-check text-secondary"></i>
    </a>
@endcan

@can('edit appointment')
    <a title="Create Lab Tests"
        href="{{ route('labprescription.create', ['appointmentId' => $id, 'patientId' => $patient_id]) }}" class="mx-3"
        data-bs-toggle="tooltip" data-bs-original-title="Add Prescriptions">
        <i class="fa-solid fa-flask-vial text-secondary"></i>
    </a>
@endcan

@can('edit appointment')
    <a title="Therapy" href="{{ route('therapy.create', ['appointmentId' => $id, 'patientId' => $patient_id]) }}"
        class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Add Prescriptions">
        <i class="fa-solid fa-bed-pulse text-secondary"></i>
    </a>
@endcan

@can('edit appointment')
    <a title="Previous Medication"
       href="{{ route('previous_medications.create', ['appointment_id' => $id, 'patient_id' => $patient_id]) }}"
       class="mx-3"
       data-bs-original-title="Previous Medication">
        <i class="fa-solid fa-notes-medical"></i>
    </a>
@endcan
<!-- <span>
    <i class="cursor-pointer fas fa-trash text-secondary" aria-hidden="true"></i>
</span> -->
