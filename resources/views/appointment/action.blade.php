@can('read appointment')
    <a href="{{ route('appointments.show', ['id' => $id]) }}" class="" data-bs-toggle="tooltip"
        data-bs-original-title="view appointment">
        <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
    </a>
@endcan

@can('edit appointment')
    <a href="{{ route('appointments.edit', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
        data-bs-original-title="Edit user">
        <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
    </a>
@endcan


@can('edit appointment')
    <a href="{{ route('prescription.create', ['appointmentId' => $id, 'patientId' => '']) }}" class="mx-3"
        data-bs-toggle="tooltip" data-bs-original-title="Add Prescriptions">
        <i class="fa-solid fa-clipboard-check text-secondary"></i>
    </a>
@endcan
<!-- <span>
    <i class="cursor-pointer fas fa-trash text-secondary" aria-hidden="true"></i>
</span> -->
