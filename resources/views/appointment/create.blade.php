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
                                                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                                    id="phone_number" class="form-control" placeholder="Enter phone number">
                                                @error('phone_number')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="is_previous_report_available">Whats App Available?</label>
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
                                                <label for="is_previous_report_available">Previous Report Available?</label>
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
                                                <input type="time" name="time_from" id="time_from"
                                                    class="form-control" value="{{ old('time_from') }}">
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
                                                    value="{{ old('time_to') }}">
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

                                        <!-- Weight -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="weight">Weight (kg)</label>
                                                <input type="number" name="weight" value="{{ old('weight') }}"
                                                    id="weight" class="form-control" placeholder="Enter weight">
                                                @error('weight')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Height -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="height">Height (cm)</label>
                                                <input type="number" name="height" value="{{ old('height') }}"
                                                    id="height" class="form-control" placeholder="Enter height">
                                                @error('height')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Occupation -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="occupation">Occupation</label>
                                                <input type="text" name="occupation" value="{{ old('occupation') }}"
                                                    id="occupation" class="form-control" placeholder="Enter occupation">
                                                @error('occupation')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Gender -->
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

                                                </div>
                                                @error('gender')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Working Hours -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="working_hours">Working Hours</label>
                                                <input type="text" name="working_hours"
                                                    value="{{ old('working_hours') }}" id="working_hours"
                                                    class="form-control" placeholder="Enter working hours">
                                                @error('working_hours')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Night Shift -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="night_shift">Night Shift</label>
                                                <div>
                                                    <input type="radio" name="night_shift" id="night_shift_yes"
                                                        value="yes" {{ old('night_shift') == 'yes' ? 'checked' : '' }}>
                                                    <label for="night_shift_yes">Yes</label>
                                                    <input type="radio" name="night_shift" id="night_shift_no"
                                                        value="no" {{ old('night_shift') == 'no' ? 'checked' : '' }}>
                                                    <label for="night_shift_no">No</label>
                                                </div>
                                                @error('night_shift')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Climatic Condition -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="climatic_condition">Climatic Condition</label>
                                                <select name="climatic_condition" value="{{ old('climatic_condition') }}"
                                                    id="climatic_condition" class="form-control">
                                                    <option value="cold">Cold</option>
                                                    <option value="hot">Hot</option>
                                                    <option value="dusty">Dusty</option>
                                                    <option value="moist">Moist</option>
                                                </select>
                                                @error('climatic_condition')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Allergic to Any Drugs -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="allergic_to_any_drugs">Allergic to Any Drugs</label>
                                                <div>
                                                    <input type="radio" name="allergic_to_any_drugs"
                                                        id="allergic_to_any_drugs_yes" value="1"
                                                        {{ old('allergic_to_any_drugs') == '1' ? 'checked' : '' }}>
                                                    <label for="allergic_to_any_drugs_yes">Yes</label>

                                                    <input type="radio" name="allergic_to_any_drugs"
                                                        id="allergic_to_any_drugs_no" value="2"
                                                        {{ old('allergic_to_any_drugs') == '2' ? 'checked' : '' }}>
                                                    <label for="allergic_to_any_drugs_no">No</label>
                                                </div>
                                                @error('allergic_to_any_drugs')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <!-- Allergic Drugs Name -->
                                        <div class="col-md-6" id="allergic_drugs_name">
                                            <div class="form-group">
                                                <label for="allergic_drugs_name">Allergic Drugs Name</label>
                                                <input type="text" name="allergic_drugs_name"
                                                    value="{{ old('allergic_drugs_name') }}" id="allergic_drugs_name"
                                                    class="form-control" placeholder="Enter allergic drugs names">
                                                @error('allergic_drugs_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Food Allergies -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="food_allergies">Food Allergies</label>
                                                <input type="text" name="food_allergies"
                                                    value="{{ old('food_allergies') }}" id="food_allergies"
                                                    class="form-control" placeholder="Enter food allergies">
                                                @error('food_allergies')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Tolerance to Lactose -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tolerance_to_lactose">Tolerance to Lactose</label>
                                                <select name="tolerance_to_lactose"
                                                    value="{{ old('tolerance_to_lactose') }}" id="tolerance_to_lactose"
                                                    class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                                @error('tolerance_to_lactose')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Last Menstrual Period (LMP) -->
                                        <div class="col-md-6" id="lmp_cpont">
                                            <div class="form-group">
                                                <label for="lmp">Last Menstrual Period (LMP)</label>
                                                <input type="text" name="lmp" id="lmp"
                                                    value="{{ old('lmp') }}" class="form-control">
                                                @error('lmp')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
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
    @endsection
