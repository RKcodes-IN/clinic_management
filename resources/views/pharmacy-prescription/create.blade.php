@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row">
            <h4>Create Prescriptions</h4>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('prescription.store') }}">
                @csrf

                <!-- Hidden Fields -->
                <input type="hidden" name="patient_id" id="patient_id" value="{{ $patientId }}">
                <input type="hidden" name="appointment_id" id="appointment_id" value="{{ $appointmentId }}">

                <!-- Pharmacy Prescriptions -->
                <div class="col-md-12 mb-4">
                    <h5>Pharmacy Prescriptions</h5>
                    <table class="table table-bordered" id="pharmacy-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="pharmacy[0][item]" class="form-control select2" required>
                                        <option value="" selected>Select Pharmacy Item</option>
                                        @foreach ($pharmacyItems as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                ({{ $item->total_stock }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="pharmacy[0][quantity]" class="form-control" required>
                                </td>
                                <td>
                                    <textarea name="pharmacy[0][message]" class="form-control"></textarea>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success add-row">Add More</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Lab Test Prescriptions -->
                <div class="col-md-12 mb-4">
                    <h5>Lab Test Prescriptions</h5>
                    <table class="table table-bordered" id="lab-test-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="labtest[0][item]" class="form-control select2" required>
                                        <option value="" selected>Select Lab Test Item</option>
                                        @foreach ($labItems as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="labtest[0][quantity]" class="form-control" required>
                                </td>
                                <td>
                                    <textarea name="labtest[0][message]" class="form-control"></textarea>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success add-row">Add More</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Submit Button -->
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary" id="submit-prescriptions">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    <!-- JavaScript for Add/Remove Rows and Initialize Select2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initializeSelect2() {
                $('.select2').select2({
                    placeholder: "Select an item",
                    allowClear: true
                });
            }

            // Initialize Select2 on page load
            initializeSelect2();

            // Add row functionality
            document.querySelectorAll('.add-row').forEach(function(button) {
                button.addEventListener('click', function() {
                    const table = this.closest('table');
                    const rowCount = table.querySelectorAll('tbody tr').length;

                    // Get specific item list based on table type
                    const items = table.id === 'pharmacy-table' ?
                        @json($pharmacyItems) :
                        @json($labItems);

                    // Generate new row
                    let itemOptions = `<option value="" selected>Select Item</option>`;
                    items.forEach(item => {
                        itemOptions += `<option value="${item.id}">${item.name}</option>`;
                    });

                    const newRow = `
                    <tr>
                        <td>
                            <select name="${table.id === 'pharmacy-table' ? 'pharmacy' : 'labtest'}[${rowCount}][item]" class="form-control select2" required>
                                ${itemOptions}
                            </select>
                        </td>
                        <td>
                            <input type="number" name="${table.id === 'pharmacy-table' ? 'pharmacy' : 'labtest'}[${rowCount}][quantity]" class="form-control" required>
                        </td>
                        <td>
                            <textarea name="${table.id === 'pharmacy-table' ? 'pharmacy' : 'labtest'}[${rowCount}][message]" class="form-control"></textarea>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                `;

                    table.querySelector('tbody').insertAdjacentHTML('beforeend', newRow);

                    // Reinitialize Select2 for new dropdown
                    initializeSelect2();
                    attachRemoveEvent();
                });
            });

            // Remove row functionality
            function attachRemoveEvent() {
                document.querySelectorAll('.remove-row').forEach(function(button) {
                    button.addEventListener('click', function() {
                        this.closest('tr').remove();
                    });
                });
            }

            attachRemoveEvent();
        });
    </script>
@endsection
