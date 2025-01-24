@extends('layouts.user_type.auth')

@section('content')
    @push('style')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.css" rel="stylesheet" />
        <style>
            #calendar {
                max-width: 1100px;
                margin: 0 auto;
                padding: 20px;
                height: 600px;
            }

            .modal-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .modal-table th,
            .modal-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .modal-table th {
                background-color: #f2f2f2;
            }
        </style>
    @endpush

    <div id="calendar"></div>

    <!-- Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Appointments for <span id="selectedDate"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="newAppointments">
                        <h6>New Appointments</h6>
                        <table class="modal-table" id="newTable">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Main Complaint</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Phone Number</th>
                                    <th>Conf Date</th>
                                    <th>Conf. Time</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="reviewAppointments">
                        <h6>Review Appointments</h6>
                        <table class="modal-table" id="reviewTable">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Main Complaint</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Phone Number</th>
                                    <th>Conf Date</th>
                                    <th>Conf. Time</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="revisitAppointments">
                        <h6>Revisit Appointments</h6>
                        <table class="modal-table" id="revisitTable">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Main Complaint</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Phone Number</th>
                                    <th>Conf Date</th>
                                    <th>Conf. Time</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(fetchInfo, successCallback, failureCallback) {
                    axios.get('/api/doctor/appointments')
                        .then(function(response) {
                            const events = [];
                            const data = response.data;

                            Object.keys(data).forEach(date => {
                                const dayData = data[date];
                                const newAppointments = dayData.new || [];
                                const reviewAppointments = dayData.review || [];
                                const revisitAppointments = dayData.revisit || [];

                                // Add event with extendedProps including arrays for the modal
                                events.push({
                                    title: '', // Custom rendering will handle title
                                    start: date,
                                    allDay: true,
                                    extendedProps: {
                                        newAppointments: newAppointments,
                                        reviewAppointments: reviewAppointments,
                                        revisitAppointments: revisitAppointments,
                                    }
                                });
                            });

                            successCallback(events);
                        })
                        .catch(function(error) {
                            console.error('Error fetching appointments:', error);
                            failureCallback(error);
                        });
                },
                eventContent: function(arg) {
                    const {
                        extendedProps
                    } = arg.event;

                    return {
                        html: `
                    <div style="text-align: center; font-size: 12px;">
                        <div><strong>New:</strong> ${extendedProps.newAppointments?.length || 0}</div>
                        <div><strong>Review:</strong> ${extendedProps.reviewAppointments?.length ||0 }</div>
                        <div><strong>Revisit:</strong> ${extendedProps.revisitAppointments?.length || 0}</div>
                    </div>
                `
                    };
                },
                eventClick: function(info) {
                    const {
                        extendedProps,
                        startStr
                    } = info.event;

                    // Populate modal
                    document.getElementById('selectedDate').textContent = startStr;

                    // Handle sections
                    toggleSection('newAppointments', 'newTable', 'New Appointments', extendedProps
                        .newAppointments);
                    toggleSection('reviewAppointments', 'reviewTable', 'Review Appointments',
                        extendedProps.reviewAppointments);
                    toggleSection('revisitAppointments', 'revisitTable', 'Revisit Appointments',
                        extendedProps.revisitAppointments);

                    // Show modal
                    const appointmentModal = new bootstrap.Modal(document.getElementById(
                        'appointmentModal'));
                    appointmentModal.show();
                }
            });

            calendar.render();

            // Function to toggle visibility of a section and populate the table
            function toggleSection(sectionId, tableId, heading, data) {
                const section = document.getElementById(sectionId);
                if (data && data.length > 0) {
                    section.style.display = ''; // Show section
                    section.querySelector('h6').textContent = `${heading} (${data.length})`; // Add count to heading
                    populateTable(tableId, data); // Populate the table
                } else {
                    section.style.display = 'none'; // Hide section
                }
            }

            // Function to populate a table
            function populateTable(tableId, data) {
                const tableBody = document.querySelector(`#${tableId} tbody`);
                tableBody.innerHTML = ''; // Clear existing rows

                if (data && data.length > 0) {
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>${item.patient_name}</td>
                    <td>${item.main_complaint}</td>
                    <td>${item.gender}</td>
                    <td>${item.age}</td>
                    <td>${item.phone_number}</td>
                    <td>${item.conf_date}</td>
                    <td>${item.conf_time}</td>
                `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="7" style="text-align: center;">No appointments</td>`;
                    tableBody.appendChild(row);
                }
            }
        });
    </script>
@endpush
