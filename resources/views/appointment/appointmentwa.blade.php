@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Appointments WA</span>
                <div class="d-flex align-items-center">
                    <input type="date" id="fromDate" value="{{ date('Y-m-d') }}" class="form-control me-2"
                        placeholder="From Date">
                    <input type="date" id="toDate" value="{{ date('Y-m-d') }}" class="form-control me-2"
                        placeholder="To Date">
                    <button id="applyFilters" class="btn btn-primary">Apply</button>

                </div>
            </div>

            <div class="table-responsive">
                <button id="copyTableData" class="btn btn-info ms-2">Copy All Data</button> <!-- New Button -->

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
        document.getElementById('applyFilters').addEventListener('click', function() {
            let fromDate = document.getElementById('fromDate').value;
            let toDate = document.getElementById('toDate').value;

            if (!fromDate || !toDate) {
                Swal.fire('Error', 'Both dates are required for filtering.', 'error');
                return;
            }

            let table = $('#appointmentdetail-wa-table').DataTable();
            console.log('Applying filters with:', {
                fromDate,
                toDate
            });

            table.ajax.url(`{{ route('appointments.wa') }}?from_date=${fromDate}&to_date=${toDate}`).load();
        });
    </script>
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
    <script>
        document.getElementById('copyTableData').addEventListener('click', function() {
            // Get the DataTable instance
            let table = $('#appointmentdetail-wa-table').DataTable();

            // Get headers as plain text
            let headers = table.columns().header().toArray().map(header => {
                return $(header).text().trim(); // Use jQuery to extract text content
            });

            // Get data for all visible rows (search and filters applied)
            let rows = table.rows({
                search: 'applied'
            }).indexes().toArray().map(rowIdx => {
                return table.columns().indexes().toArray().map(colIdx => {
                    // Fetch the cell data and strip HTML tags
                    let cellNode = table.cell(rowIdx, colIdx).node();
                    let cellText = $(cellNode).text().trim(); // Extract visible text
                    return cellText;
                }).join('\t'); // Join columns with a tab delimiter
            });

            // Combine headers and rows
            let tableText = [headers.join('\t'), ...rows].join('\n');

            // Copy to clipboard
            navigator.clipboard.writeText(tableText)
                .then(() => {
                    Swal.fire('Copied!', 'Table data has been copied to clipboard.', 'success');
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to copy data to clipboard.', 'error');
                });
        });
    </script>
@endpush
