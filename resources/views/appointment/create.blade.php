@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Appointment</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('appointments.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Is this a new patient? -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Is this a new patient?</label>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="is_new_patient"
                                                id="new_patient_radio" value="yes"
                                                {{ old('is_new_patient', 'yes') === 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="new_patient_radio">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="is_new_patient"
                                                id="existing_patient_radio" value="no"
                                                {{ old('is_new_patient') === 'no' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="existing_patient_radio">No</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Patient Name / Existing Patient -->
                                <div class="col-md-6">
                                    <div id="new_patient_fields" style="{{ old('is_new_patient', 'yes') === 'no' ? 'display:none;' : '' }}">
                                        <div class="form-group">
                                            <label for="patient_name">Patient Name</label>
                                            <input type="text" name="patient_name" id="patient_name"
                                                class="form-control" value="{{ old('patient_name') }}">
                                            @error('patient_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="existing_patient_fields" style="{{ old('is_new_patient') === 'yes' ? 'display:none;' : '' }}">
                                        <div class="form-group">
                                            <label for="patient_id">Existing Patient</label>
                                            <select name="patient_id" id="patient_id" class="form-control">
                                                <option value="">Select Patient</option>
                                                @foreach ($patients as $patient)
                                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                        {{ $patient->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('patient_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
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
                                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                        @error('phone_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
                                        @error('main_complaint')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Available Date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="available_date">Available Date</label>
                                        <input type="date" name="available_date" id="available_date"
                                            class="form-control" value="{{ old('available_date') }}">
                                        @error('available_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Time From -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="time_from">Time From</label>
                                        <input type="time" name="time_from" id="time_from" class="form-control" value="{{ old('time_from') }}">
                                        @error('time_from')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Time To -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="time_to">Time To</label>
                                        <input type="time" name="time_to" id="time_to" class="form-control" value="{{ old('time_to') }}">
                                        @error('time_to')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Message -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea name="message" id="message" class="form-control">{{ old('message') }}</textarea>
                                        @error('message')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach (\App\Models\Appointment::getStatusLabels() as $key => $label)
                                                <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Trigger change event on page load to show the correct fields
            $('input[name="is_new_patient"]:checked').trigger('change');
            $('#existing_patient_fields').hide();

            // Toggle fields based on radio button change
            $('input[name="is_new_patient"]').change(function() {
                if ($(this).val() === 'yes') {
                    $('#new_patient_fields').show();
                    $('#existing_patient_fields').hide();
                } else {
                    $('#new_patient_fields').hide();
                    $('#existing_patient_fields').show();
                }
            });
        });
    </script>
@endsection
