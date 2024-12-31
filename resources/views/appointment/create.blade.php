    @extends('layouts.user_type.auth')

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Create Appointment & Health Evaluation</div>

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

                                <!-- Patient Selection -->
                                <fieldset>
                                    <legend>Patient Details</legend>
                                    <div class="row">
                                        <!-- Patient Name / Existing Patient -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="patient">Patient</label>
                                                <select id="patient" name="patient_id" class="form-control"
                                                    style="width:100%"></select>
                                                @error('patient_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <!-- Hidden field to capture new patient name -->
                                            <input type="hidden" id="new_patient_name" name="new_patient_name">
                                        </div>

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
                                                @error('doctor_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- Doctor and Contact Information -->
                                <fieldset>
                                    <legend>Contact Information</legend>
                                    <div class="row">
                                        <!-- Doctor -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone_number">Phone Number</label>
                                                <div class="input-group">
                                                    <input type="text" name="country_code" id="country_code"
                                                        class="form-control" value="{{ old('country_code', '+91') }}"
                                                        placeholder="+91" style="max-width: 80px;">
                                                    <input type="text" name="phone_number" id="phone_number"
                                                        class="form-control" value="{{ old('phone_number') }}"
                                                        placeholder="Enter phone number">
                                                </div>
                                                @error('country_code')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @error('phone_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <input type="text" name="country" id="country" class="form-control"
                                                    value="{{ old('country') }}" placeholder="Enter country" required>
                                                @error('country')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state">City</label>
                                                <input type="text" name="city" id="city" class="form-control"
                                                    value="{{ old('city') }}" placeholder="Enter city" required>
                                                @error('city')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="is_previous_report_available">Whatsapp Available?</label>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="is_previous_report_available"
                                                        id="is_previous_report_available" value="1"
                                                        {{ old('is_previous_report_available') ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="is_previous_report_available">Yes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="consultation_type">Consultation Type</label>

                                            <div class="form-group">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="consultation_type"
                                                        id="online" value="1"
                                                        {{ old('consultation_type') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="online">Online</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="consultation_type"
                                                        id="physical" value="2"
                                                        {{ old('consultation_type') == '2' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="physical">Physical</label>
                                                </div>
                                            </div>
                                        </div>




                                        <!-- Email -->

                                    </div>

                                    <div class="row">
                                        <!-- Phone Number -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email"
                                                    value="{{ old('email') }}" class="form-control"
                                                    placeholder="Enter patient email">
                                                @error('email')
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
                                </fieldset>

                                <!-- Appointment Details -->
                                <fieldset>
                                    <legend>Appointment Details</legend>
                                    <div class="row">
                                        <!-- Previous Report Available -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_previous_report_available">Previous Report
                                                    Available?</label>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="is_previous_report_available"
                                                        id="is_previous_report_available" value="1"
                                                        {{ old('is_previous_report_available') ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="is_previous_report_available">Yes</label>
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
                                                <label for="available_date">Prefferable Date</label>
                                                <input type="date" name="available_date" id="available_date"
                                                    class="form-control"
                                                    value="{{ old('available_date', date('Y-m-d')) }}">
                                                @error('available_date')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Time From -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="time_from">Time From</label>
                                                <input type="time" name="time_from" id="time_from"
                                                    class="form-control" value="{{ old('time_from', date('H:i')) }}">
                                                @error('time_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Time To -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="time_to">Time To</label>
                                                <input type="time" name="time_to" id="time_to" class="form-control"
                                                    value="{{ old('time_to', date('H:i')) }}">
                                                @error('time_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Additional Information -->
                                <fieldset>
                                    <legend>Additional Information</legend>
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
                                                        <option value="{{ $key }}"
                                                            {{ old('status') == $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('status')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Type</label>
                                                <select name="type" id="type" class="form-control">
                                                    @foreach (\App\Models\Appointment::typeLables() as $key => $label)
                                                        <option value="{{ $key }}"
                                                            {{ old('type') == $key ? 'selected' : '' }}>
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
                                </fieldset>

                                <!-- Health Evaluation -->
                                <fieldset>
                                    <div class="row">
                                        <!-- Age -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="age">Age</label>
                                                <input type="number" name="age" value="{{ old('age') }}"
                                                    id="age" class="form-control" placeholder="Enter age">
                                                @error('age')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Gender</label>
                                                <div class="row">
                                                    <div class="col-2">
                                                        <input type="radio" name="gender" id="male"
                                                            value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="male">Male</label>
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="radio" name="gender" id="female"
                                                            value="female"
                                                            {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="female">Female</label>
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="radio" name="gender" id="other"
                                                            value="other"
                                                            {{ old('gender') == 'other' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="other">Others</label>
                                                    </div>
                                                </div>

                                                @error('gender')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>


                                    <div class="row">
                                        <!-- Gender -->

                                        <!-- Working Hours -->
                                        {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="working_hours">Working Hours</label>
                                                <input type="text" name="working_hours"
                                                    value="{{ old('working_hours') }}" id="working_hours"
                                                    class="form-control" placeholder="Enter working hours">
                                                @error('working_hours')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div> --}}
                                    </div>







                                </fieldset>

                                <!-- Submit Button -->
                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Create Appointment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

        <script>
            $(document).ready(function() {
                $('#patient').select2({
                    ajax: {
                        url: '{{ route('patients.search') }}', // Make sure this route returns the patient data as JSON
                        dataType: 'json',
                        delay: 250, // Add delay for better performance on typing
                        data: function(params) {
                            return {
                                query: params.term // Send the search query to the server
                            };
                        },

                        processResults: function(data) {
                            return {


                                results: data.map(function(item) {
                                    console.log(item);

                                    return {
                                        id: item.id,
                                        text: item
                                            .name + " (" + item
                                            .phone_number + ")" + " (" + item
                                            .place +
                                            ")" // Assuming 'name' is the patient name in your JSON response
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    tags: true, // Enable typing of new entries
                    createTag: function(params) {
                        // Create new patient entry if it doesn't exist
                        return {
                            id: params.term,
                            text: params.term,
                            newOption: true // Mark it as a new option
                        };
                    },
                    templateResult: function(data) {
                        // Highlight new options differently in the dropdown
                        var $result = $("<span></span>");
                        $result.text(data.text);

                        if (data.newOption) {
                            $result.append(" <em>(new)</em>");
                        }
                        return $result;
                    }
                });

                // Handle selection of patient
                $('#patient').on('select2:select', function(e) {
                    var data = e.params.data;

                    if (data.newOption) {
                        // If the patient is new, set the hidden input field with the new patient's name
                        $('#new_patient_name').val(data.text);
                    } else {
                        // If an existing patient is selected, clear the hidden input field
                        $('#new_patient_name').val('');
                    }
                });
            });

            $(document).ready(function() {
                // Initially hide the container on page load
                $('#allergic_drugs_name').hide();

                // Show or hide the field based on the radio button selection
                $('input[name="allergic_to_any_drugs"]').change(function() {
                    if ($(this).val() == '1') {
                        $('#allergic_drugs_name').show();
                    } else {
                        $('#allergic_drugs_name').hide();
                    }
                });
            });


            $(document).ready(function() {
                // Initially hide the container on page load
                $('#lmp_cpont').hide();

                // Show or hide the field based on the radio button selection
                $('input[name="gender"]').change(function() {
                    if ($(this).val() == 'male') {
                        $('#lmp_cpont').hide();
                    } else {
                        $('#lmp_cpont').show();
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                // Handle the change event of the patient dropdown
                $('#patient').change(function() {
                    let val = $(this).val(); // Get the selected value
                    console.log(val);

                    if (val) { // Ensure a valid ID is selected
                        $.ajax({
                            type: "get",
                            url: "{{ url('appointments') }}/" + val +
                                "/getpatientdetail", // Append the ID to the URL
                            dataType: "json",
                            success: function(response) {
                                if (response) {
                                    console.log(response)
                                    $('#phone_number').val(response.details.phone_number || '');
                                    $('#email').val(response.details.email || '');
                                    $('#address').val(response.details.address || '');
                                    $('#main_complaint').val(response.details.main_complaint || '');
                                    $('#available_date').val(response.details.available_date || '');
                                    $('#time_from').val(response.details.time_from || '');
                                    $('#time_to').val(response.details.time_to || '');
                                    $('#age').val(response.details.age || '');
                                    $('#weight').val(response.details.weight || '');
                                    $('#height').val(response.details.height || '');
                                    $('#occupation').val(response.details.occupation || '');

                                    // Gender radio button
                                    if (response.details.gender === 'Male') {
                                        $('#male').prop('checked', true);
                                    } else if (response.details.gender === 'Female') {
                                        $('#female').prop('checked', true);
                                    }

                                    // Checkbox example (for boolean fields)
                                    $('#is_previous_report_available').prop('checked', response
                                        .details.is_previous_report_available || false);
                                }
                            },
                            error: function(xhr) {
                                console.error("Error fetching patient details:", xhr.responseText);
                            }
                        });
                    } else {
                        console.warn("No patient ID selected.");
                    }
                });
            });
        </script>
    @endsection
