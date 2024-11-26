@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Health Evaluation</div>

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

                        <form action="{{ route('healthevalution.store') }}" method="POST">
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
                                            <label for="date">Visit's Date</label>
                                            <input type="date" name="date" id="date" class="form-control">
                                        </div>

                                    </div>
                                </div>
                            </fieldset>

                            <!-- Doctor and Contact Information -->
                            <fieldset>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                                            @error('address')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="age">Age</label>
                                            <input type="number" name="age" value="{{ old('age') }}" id="age"
                                                class="form-control" placeholder="Enter age">
                                            @error('age')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="weight">Weight (kg)</label>
                                            <input type="number" name="weight" value="{{ old('weight') }}" id="weight"
                                                class="form-control" placeholder="Enter weight">
                                            @error('weight')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="height">Height (cm)</label>
                                            <input type="number" name="height" value="{{ old('height') }}" id="height"
                                                class="form-control" placeholder="Enter height">
                                            @error('height')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

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

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select name="gender" id="gender" value="{{ old('gender') }}"
                                                class="form-control">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                            @error('gender')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="working_hours">Working Hours</label>
                                            <input type="text" name="working_hours" value="{{ old('working_hours') }}"
                                                id="working_hours" class="form-control" placeholder="Enter working hours">
                                            @error('working_hours')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

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
                            </fieldset>
                            <!-- resources/views/health-evalution/create.blade.php -->
                            <legend>Past History</legend>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Past History Name</th>
                                        <th>Yes</th>
                                        <th>No</th>
                                        <th>Since (Year)</th>
                                        <th>Trade Name</th>
                                        <th>Chemical</th>
                                        <th>Dose/Freq</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($pastHistory as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $history->name }}</td>
                                            <td><input type="radio" name="history[{{ $history->id }}][yes_no]"
                                                    value="yes"
                                                    {{ old("history.{$history->id}.yes_no") == 'yes' ? 'checked' : '' }}>
                                            </td>
                                            <td><input type="radio" name="history[{{ $history->id }}][yes_no]"
                                                    value="no"
                                                    {{ old("history.{$history->id}.yes_no") == 'no' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="history[{{ $history->id }}][since]"
                                                    class="form-control"
                                                    value="{{ old("history.{$history->id}.since") }}">
                                            </td>
                                            <td>
                                                <input type="text" name="history[{{ $history->id }}][trade_name]"
                                                    class="form-control"
                                                    value="{{ old("history.{$history->id}.trade_name") }}">
                                            </td>
                                            <td>
                                                <input type="text" name="history[{{ $history->id }}][chemical]"
                                                    class="form-control"
                                                    value="{{ old("history.{$history->id}.chemical") }}">
                                            </td>
                                            <td>
                                                <input type="text" name="history[{{ $history->id }}][dose_freq]"
                                                    class="form-control"
                                                    value="{{ old("history.{$history->id}.dose_freq") }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- resources/views/health-evalution/create.blade.php -->
                            <legend>Surgical History</legend>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Surgery/Condition</th>
                                        <th>Yes</th>
                                        <th>No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $surgeries = [
                                            'CABG' => 'CABG (By Pass)',
                                            'PTCA' => 'PTCA (Stenting)',
                                            'Appendix' => 'Appendix',
                                            'GallBladder' => 'Gall Bladder',
                                            'Hystectomy' => 'Hystectomy',
                                            'Caesarean' => 'Caesarean',
                                            'AnyOther' => 'Any Other',
                                        ];
                                    @endphp

                                    @foreach ($surgeries as $key => $surgery)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $surgery }}</td>
                                            <td>
                                                <input type="radio" name="surgery[{{ $key }}]" value="yes"
                                                    {{ old("habit.{$key}") == 'yes' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="radio" name="surgery[{{ $key }}]" value="no"
                                                    {{ old("habit.{$key}") == 'no' ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- resources/views/health-evalution/create.blade.php -->

                            <legend>Addications</legend>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Habit</th>
                                        <th>Yes</th>
                                        <th>No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $habits = [
                                            'smoking' => 'Smoking',
                                            'alcohol' => 'Alcohol',
                                            'gutka' => 'Gutka',
                                            'tea' => 'Tea',
                                            'coffee' => 'Coffee',
                                            'anyother' => 'Any Other',
                                        ];
                                    @endphp

                                    @foreach ($habits as $key => $habit)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $habit }}</td>
                                            <td>
                                                <input type="radio" name="habit[{{ $key }}]" value="yes"
                                                    {{ old("surgery.{$key}") == 'yes' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="radio" name="habit[{{ $key }}]" value="no"
                                                    {{ old("surgery.{$key}") == 'no' ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                        <input type="text" name="food_allergies" value="{{ old('food_allergies') }}"
                                            id="food_allergies" class="form-control" placeholder="Enter food allergies">
                                        @error('food_allergies')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tolerance to Lactose -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tolerance_to_lactose">Tolerance to Lactose</label>
                                        <select name="tolerance_to_lactose" value="{{ old('tolerance_to_lactose') }}"
                                            id="tolerance_to_lactose" class="form-control">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lmp">Last Menstrual Period (LMP)</label>
                                        <select name="lmp" id="lmp" class="form-control">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
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
            // Initialize select2
            $('#patient').select2({
                ajax: {
                    url: '{{ route('patients.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                },
                tags: true,
                createTag: function(params) {
                    return {
                        id: params.term, // Use the entered text as ID for new entries
                        text: params.term,
                        newOption: true // Mark as new option
                    };
                },
                templateResult: function(data) {
                    var $result = $("<span></span>");
                    $result.text(data.text);
                    if (data.newOption) {
                        $result.append(" <em>(new)</em>");
                    }
                    return $result;
                }
            });

            // Event listener for dropdown close
            $('#patient').on('select2:close', function(e) {
                var selectedOption = $('#patient').select2('data')[0]; // Get the selected option

                if (selectedOption.newOption) {
                    // If it's a new patient, set the new patient's name in the hidden field
                    $('#new_patient_name').val(selectedOption.text);
                    $('#patient').val(''); // Clear patient ID as it's a new patient
                } else {
                    // If it's an existing patient, clear new_patient_name and store patient_id
                    $('#new_patient_name').val(''); // Clear the hidden field for new patient name
                    $('#patient_id').val(selectedOption.id); // Set the patient ID
                }
            });



            // Handle the allergic_to_any_drugs checkbox or radio button
            $('input[name="allergic_to_any_drugs"]').change(function() {
                if ($(this).val() == '1') {
                    // Show the allergic drugs name field if the user selects "yes"
                    $('#allergic_drugs_name').show();
                } else {
                    // Hide it if the user selects "no"
                    $('#allergic_drugs_name').hide();
                }
            });
        });
    </script>
@endsection
