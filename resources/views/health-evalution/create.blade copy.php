@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Appointment</div>

                    <div class="card-body">
                        <!-- Success and error messages -->
                        <div id="alert-success" class="alert alert-success" style="display:none;"></div>
                        <div id="alert-error" class="alert alert-danger" style="display:none;"></div>

                        <form id="appointment-form" action="#">
                            @csrf

                            <div class="row">
                                <!-- Patient Name / Existing Patient -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient">Patient</label>
                                        <select id="patient" name="patient_id" class="form-control"
                                            style="width:100%"></select>
                                        <input type="hidden" id="new_patient_name" name="new_patient_name">
                                        <small class="text-danger" id="patient_id_error"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Doctor -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">Doctor</label>
                                        <select name="doctor_id" id="doctor_id" class="form-control">
                                            <option value="">Select Doctor</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="doctor_id_error"></small>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Enter patient email">
                                        <small class="text-danger" id="email_error"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control"
                                            placeholder="Enter phone number">
                                        <small class="text-danger" id="phone_number_error"></small>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                                        <small class="text-danger" id="address_error"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Previous Report Available -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_previous_report_available">Previous Report Available?</label>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="is_previous_report_available" id="is_previous_report_available"
                                                value="1" {{ old('is_previous_report_available') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_previous_report_available">Yes</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Complaint -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="main_complaint">Main Complaint</label>
                                        <textarea name="main_complaint" id="main_complaint" class="form-control">{{ old('main_complaint') }}</textarea>
                                        <small class="text-danger" id="main_complaint_error"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Available Date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="available_date">Available Date</label>
                                        <input type="date" name="available_date" id="available_date" class="form-control"
                                            value="{{ old('available_date') }}">
                                        <small class="text-danger" id="available_date_error"></small>
                                    </div>
                                </div>

                                <!-- Time From -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="time_from">Time From</label>
                                        <input type="time" name="time_from" id="time_from" class="form-control"
                                            value="{{ old('time_from') }}">
                                        <small class="text-danger" id="time_from_error"></small>
                                    </div>
                                </div>

                                <!-- Time To -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="time_to">Time To</label>
                                        <input type="time" name="time_to" id="time_to" class="form-control"
                                            value="{{ old('time_to') }}">
                                        <small class="text-danger" id="time_to_error"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Message -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea name="message" id="message" class="form-control">{{ old('message') }}</textarea>
                                        <small class="text-danger" id="message_error"></small>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach (\App\Models\Appointment::getStatusLabels() as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('status') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="status_error"></small>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" id="submit-btn">Create Appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for patient selection
            $('#patient').select2({
                ajax: {
                    url: '{{ route("patients.search") }}',
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                },
                tags: true,
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newOption: true
                    };
                }
            });

            // Capture the input and detect if it's a new patient or existing
            $('#patient').on('select2:select', function(e) {
                var selected = e.params.data;
                if (selected.newOption) {
                    $('#new_patient_name').val(selected.text);
                    $('#patient').val('');
                } else {
                    $('#new_patient_name').val('');
                }
            });

            // AJAX form submission on button click
            $('#submit-btn').on('click', function(e) {
                e.preventDefault();  // Prevent the default action of the button

                var formData = $('#appointment-form').serialize();  // Get form data

                $.ajax({
                    url: "{{ route('appointments.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        // Display success message and clear form fields
                        $('#alert-success').text(response.message).show();
                        $('#alert-error').hide();
                        $('#appointment-form')[0].reset();  // Clear the form
                        $('#patient').val(null).trigger('change');  // Reset Select2 field
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $('#alert-success').hide();
                        $('#alert-error').text(xhr.responseJSON.message).show();

                        // Clear previous error messages
                        $('.text-danger').text('');

                        // Display field-specific errors
                        $.each(errors, function(key, value) {
                            $('#' + key + '_error').text(value[0]);
                        });
                    }
                });
            });
        });
    </script>
@endsection
