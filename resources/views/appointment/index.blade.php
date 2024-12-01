@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Appointments</span>
            </div>
            <div class="table-responsive">
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module', 'responsive' => true]) }}


    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

    <script>
        function showApproveForm(appointmentId) {
            Swal.fire({
                title: 'Approve Appointment',
                html: `
            <form id="approveForm-${appointmentId}">
                <label for="approve_date">Approve Date:</label>
                <input type="date" id="approve_date" class="form-control mb-3" name="approve_date" required>
                <label for="slot_time">Slot Time:</label>
                <input type="time" id="slot_time" class="form-control" name="slot_time" required>
            </form>
        `,
                showCancelButton: true,
                confirmButtonText: 'Approve',
                preConfirm: () => {
                    const modal = Swal.getHtmlContainer();
                    const approveDateInput = modal.querySelector('#approve_date');
                    const slotTimeInput = modal.querySelector('#slot_time');

                    const approveDate = approveDateInput.value;
                    const slotTime = slotTimeInput.value;

                    if (!approveDate || !slotTime) {
                        Swal.showValidationMessage('Both fields are required.');
                        return false;
                    }

                    return {
                        approveDate,
                        slotTime
                    };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        approveDate,
                        slotTime
                    } = result.value;

                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    console.log('CSRF Token Element:', csrfTokenElement);

                    const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;

                    if (!csrfToken) {
                        Swal.fire('Error', 'CSRF token is missing. Please refresh the page.', 'error');
                        return;
                    }

                    fetch(`/appointments/${appointmentId}/approve`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                approve_date: approveDate,
                                slot_time: slotTime
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success', 'Appointment approved successfully', 'success').then(
                            () => {
                                    location.reload(); // Refresh the page
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'An unexpected error occurred.', 'error'));
                }
            });
        }



        function rejectAppointment(appointmentId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to reject this appointment?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an API call or submit a form for rejection
                    fetch(`/appointments/${appointmentId}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Rejected!', 'Appointment rejected successfully', 'success');
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'An unexpected error occurred', 'error'));
                }
            });
        }
    </script>
@endpush
