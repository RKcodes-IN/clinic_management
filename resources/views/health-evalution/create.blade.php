@extends('layouts.user_type.auth')

@section('content')
    <style>
        /*Background color*/


        /*form styles*/

        #msform fieldset .form-card {

            /*stacking fieldsets aboveeach other*/
            position: relative;
        }

        #msform fieldset {


            /*stacking fieldsets above each other*/
            position: relative;
        }

        /*Hide all except first fieldset*/
        #msform fieldset:not(:first-of-type) {
            display: none;
        }

        #msform fieldset .form-card {
            text-align: left;
            color: #9E9E9E;
            padding: 0 12px;
        }

        /* #msform input,
                                                                                                                                                                                    #msform textarea {
                                                                                                                                                                                        padding: 0px 8px 4px 8px;
                                                                                                                                                                                        border: none;
                                                                                                                                                                                        border-bottom: 1px solid #ccc;
                                                                                                                                                                                        border-radius: 0px;
                                                                                                                                                                                        margin-bottom: 25px;
                                                                                                                                                                                        margin-top: 2px;
                                                                                                                                                                                        width: 100%;
                                                                                                                                                                                        box-sizing: border-box;
                                                                                                                                                                                        color: #2C3E50;
                                                                                                                                                                                        font-size: 16px;
                                                                                                                                                                                        letter-spacing: 1px;
                                                                                                                                                                                    } */

        #msform input:focus,
        #msform textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: none;
            /* font-weight: bold; */
            border-bottom: 2px solid skyblue;
            outline-width: 0;
        }

        /*Blue Buttons*/
        #msform .action-button {
            width: 100px;
            background: skyblue;
            /* font-weight: bold; */
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px;
        }

        #msform .action-button:hover,
        #msform .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue;
        }

        /*Previous Buttons*/
        #msform .action-button-previous {
            width: 100px;
            background: #616161;
            /* font-weight: bold; */
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px;
        }

        #msform .action-button-previous:hover,
        #msform .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #616161;
        }

        /*Dropdown List Exp Date*/
        select.list-dt {
            border: none;
            outline: 0;
            border-bottom: 1px solid #ccc;
            padding: 2px 5px 3px 5px;
            margin: 2px;
        }

        select.list-dt:focus {
            border-bottom: 2px solid skyblue;
        }

        /*The background card*/
        .card {
            z-index: 0;
            border: none;
            border-radius: 0.5rem;
            position: relative;
        }

        /*FieldSet headings*/
        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            /* font-weight: bold; */
            text-align: left;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 25%;
            float: left;
            position: relative;
        }

        /*Icons in the ProgressBar*/
        #progressbar #account:before {
            font-family: FontAwesome;
            content: "\f023";
        }

        #progressbar #personal:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #payment:before {
            font-family: FontAwesome;
            content: "\f09d";
        }

        #progressbar #confirm:before {
            font-family: FontAwesome;
            content: "\f00c";
        }

        /*ProgressBar before any progress*/
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: skyblue;
        }

        /*Imaged Radio Buttons*/
        .radio-group {
            position: relative;
            margin-bottom: 25px;
        }

        .radio {
            display: inline-block;
            width: 204;
            height: 104;
            border-radius: 0;
            background: lightblue;
            box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            cursor: pointer;
            margin: 8px 2px;
        }

        .radio:hover {
            box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .radio.selected {
            box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1);
        }

        /*Fit image in bootstrap div*/
        .fit-image {
            width: 100%;
            object-fit: cover;
        }
    </style>
    <!-- MultiStep Form -->
    <div class="container-fluid" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-12 text-center p-0 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                    <h2><strong>Health Evaluation</strong></h2>
                    <p>Fill all form field to go to next step</p>
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li class="active" id="account"><strong>Basic Details</strong></li>
                                    <li id="personal"><strong>Personal History</strong></li>
                                    <li id="payment"><strong>Physician Assessment</strong></li>
                                    <li id="confirm"><strong>Finish</strong></li>
                                </ul>
                                <!-- fieldsets -->
                                <fieldset>
                                    <div class="form-card">
                                        <div class="row">
                                            <!-- Patient Name / Existing Patient -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="patient_id">Patient Name</label>
                                                    <select name="patient_id" id="patient_id" class="form-control select2"
                                                        required>
                                                        <option value="">Select Patient</option>
                                                        @if ($patients instanceof \Illuminate\Database\Eloquent\Collection)
                                                            @foreach ($patients as $patient)
                                                                <option value="{{ $patient->id }}"
                                                                    {{ request()->query('patient_id') == $patient->id ? 'selected' : '' }}>
                                                                    {{ $patient->name }}
                                                                </option>
                                                            @endforeach
                                                        @elseif ($patients)
                                                            <option value="{{ $patients->id }}" selected>
                                                                {{ $patients->name }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date">Visit's Date</label>
                                                    <input type="date" value="{{ date('Y-m-d') }}" name="date"
                                                        id="date" class="form-control">
                                                </div>

                                            </div>
                                        </div>
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
                                                    <input type="number" name="age" value="{{ old('age') }}"
                                                        id="age" class="form-control" placeholder="Enter age">
                                                    @error('age')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

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
                                                                value="male"
                                                                {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="male">Male</label>
                                                        </div>
                                                        <div class="col-3">
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

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="night_shift">Night Shift</label>
                                                    <div>
                                                        <input type="radio" name="night_shift" id="night_shift_yes"
                                                            value="yes"
                                                            {{ old('night_shift') == 'yes' ? 'checked' : '' }}>
                                                        <label for="night_shift_yes">Yes</label>
                                                        <input type="radio" name="night_shift" id="night_shift_no"
                                                            value="no"
                                                            {{ old('night_shift') == 'no' ? 'checked' : '' }}>
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
                                                    <select name="climatic_condition"
                                                        value="{{ old('climatic_condition') }}" id="climatic_condition"
                                                        class="form-control">
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
                                                        <td><input type="radio"
                                                                name="history[{{ $history->id }}][yes_no]"
                                                                value="yes"
                                                                {{ old("history.{$history->id}.yes_no") == 'yes' ? 'checked' : '' }}>
                                                        </td>
                                                        <td><input type="radio"
                                                                name="history[{{ $history->id }}][yes_no]"
                                                                value="no"
                                                                {{ old("history.{$history->id}.yes_no") == 'no' ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="history[{{ $history->id }}][since]"
                                                                class="form-control"
                                                                value="{{ old("history.{$history->id}.since") }}">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="history[{{ $history->id }}][trade_name]"
                                                                class="form-control"
                                                                value="{{ old("history.{$history->id}.trade_name") }}">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="history[{{ $history->id }}][chemical]"
                                                                class="form-control"
                                                                value="{{ old("history.{$history->id}.chemical") }}">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="history[{{ $history->id }}][dose_freq]"
                                                                class="form-control"
                                                                value="{{ old("history.{$history->id}.dose_freq") }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

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
                                                            <input type="radio" name="surgery[{{ $key }}]"
                                                                value="yes"
                                                                {{ old("habit.{$key}") == 'yes' ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="surgery[{{ $key }}]"
                                                                value="no"
                                                                {{ old("habit.{$key}") == 'no' ? 'checked' : '' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <!-- resources/views/health-evalution/create.blade.php -->

                                        <legend>Addictions</legend>

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
                                                            <input type="radio" name="habit[{{ $key }}]"
                                                                value="yes"
                                                                {{ old("surgery.{$key}") == 'yes' ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="habit[{{ $key }}]"
                                                                value="no"
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
                                                        value="{{ old('tolerance_to_lactose') }}"
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
                                    </div>
                                    <input type="button" name="next" class="next action-button" value="Next Step" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <h4 class="fs-title">Personal History</h4>

                                        <div class="form-group">
                                            <label>Diet Preference:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="diet_preference"
                                                    value="vegetarian" required>
                                                <label class="form-check-label">Vegan / Vegetarian</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="diet_preference"
                                                    value="non_vegetarian">
                                                <label class="form-check-label">Non-Vegetarian</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Do you have irregular meal timing?</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="meal_timing"
                                                    value="yes" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="meal_timing"
                                                    value="no">
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>If Yes:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="meal_timing_desc"
                                                    value="Less than 2 times a week" required>
                                                <label class="form-check-label">Less than 2 times a week</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="meal_timing_desc"
                                                    value="2 to 4 times a week">
                                                <label class="form-check-label">2 to 4 times a week</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="meal_timing_desc"
                                                    value="More than 4 times a week">
                                                <label class="form-check-label">More than 4 times a week</label>
                                            </div>
                                        </div>

                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Meal</th>
                                                    <th>Time</th>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Breakfast</td>
                                                    <td><input type="text" name="breakfast_time" class="form-control"
                                                            placeholder="Enter time"></td>
                                                    <td><input type="text" name="breakfast_item" class="form-control"
                                                            placeholder="Enter item"></td>
                                                    <td><input type="text" name="breakfast_quantity"
                                                            class="form-control" placeholder="Enter quantity"></td>
                                                </tr>
                                                <tr>
                                                    <td>Lunch</td>
                                                    <td><input type="text" name="lunch_time" class="form-control"
                                                            placeholder="Enter time"></td>
                                                    <td><input type="text" name="lunch_item" class="form-control"
                                                            placeholder="Enter item"></td>
                                                    <td><input type="text" name="lunch_quantity" class="form-control"
                                                            placeholder="Enter quantity"></td>
                                                </tr>
                                                <tr>
                                                    <td>Dinner</td>
                                                    <td><input type="text" name="dinner_time" class="form-control"
                                                            placeholder="Enter time"></td>
                                                    <td><input type="text" name="dinner_item" class="form-control"
                                                            placeholder="Enter item"></td>
                                                    <td><input type="text" name="dinner_quantity" class="form-control"
                                                            placeholder="Enter quantity"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <hr>

                                        <h4 class="fs-title">Physical Fitness</h4>
                                        <div class="form-group form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="fitness_option"
                                                value="exercise">
                                            <label class="form-check-label">Exercise</label>
                                        </div>
                                        <div class="form-group form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="fitness_option"
                                                value="yoga">
                                            <label class="form-check-label">Yoga</label>
                                        </div>
                                        <div class="form-group">
                                            <label>Duration:</label>
                                            <input type="text" name="fitness_duration" class="form-control w-25"
                                                placeholder="Enter duration">
                                        </div>
                                        <div class="form-group">
                                            <label>Distance:</label>
                                            <input type="text" name="fitness_distance" class="form-control w-25"
                                                placeholder="Enter distance">
                                        </div>

                                        <hr>

                                        <h4 class="fs-title">Appetite</h4>
                                        <div class="form-group">
                                            <label>Appetite:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="appetite"
                                                    value="poor" required>
                                                <label class="form-check-label">Poor</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="appetite"
                                                    value="good">
                                                <label class="form-check-label">Good</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="appetite"
                                                    value="very_good">
                                                <label class="form-check-label">Very Good</label>
                                            </div>
                                        </div>

                                        <hr>

                                        <h4 class="fs-title">Digestion</h4>
                                        <div class="form-group">
                                            <label>Digestion:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="digestion"
                                                    value="poor" required>
                                                <label class="form-check-label">Poor</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="digestion"
                                                    value="good">
                                                <label class="form-check-label">Good</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="digestion"
                                                    value="very_good">
                                                <label class="form-check-label">Very Good</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Past history of Hyper Acidity (Burning Pain):</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="hyper_acidity"
                                                    value="yes" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="hyper_acidity"
                                                    value="no">
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Gas Problems / Flatulence Bloating-Heavy (Burning Pain):</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gas_problem"
                                                    value="yes" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gas_problem"
                                                    value="no">
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>

                                        <hr>

                                        <h4 class="fs-title">Stool</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No. of Times</th>
                                                    <th>Consistency</th>
                                                    <th>Urgency</th>
                                                    <th>Loose Motion with Change of Food</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" name="stool_time" class="form-control"
                                                            placeholder="Enter number of times"></td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="stool_consistency" value="hard">
                                                            <label class="form-check-label">Hard</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="stool_consistency" value="semi_solid">
                                                            <label class="form-check-label">Semi-Solid</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="stool_consistency" value="soft">
                                                            <label class="form-check-label">Soft</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="stool_urgency" value="yes">
                                                            <label class="form-check-label">Yes</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="stool_urgency" value="no">
                                                            <label class="form-check-label">No</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="loose_motion_food" value="yes">
                                                            <label class="form-check-label">Yes</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="loose_motion_food" value="no">
                                                            <label class="form-check-label">No</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <hr>

                                        <h4 class="fs-title">Urine</h4>
                                        <div class="form-group">
                                            <label>No. of times a day:</label>
                                            <input type="text" name="urine_day" class="form-control"
                                                placeholder="Enter number of times a day">
                                        </div>
                                        <div class="form-group">
                                            <label>No. of times at night:</label>
                                            <input type="text" name="urine_night" class="form-control"
                                                placeholder="Enter number of times at night">
                                        </div>
                                        <div class="form-group">
                                            <label>Any difficulty:</label>
                                            <textarea name="urine_difficulty" class="form-control" placeholder="Describe any difficulty"></textarea>
                                        </div>

                                        <hr>

                                        <h4 class="fs-title">Sleep</h4>
                                        <div class="form-group row">
                                            <div class="col-sm-4">

                                                <label class="">Time From:</label>
                                                <input type="text" name="sleep_time_from" class="form-control"
                                                    placeholder="Enter time from">
                                            </div>
                                            <div class="col-sm-4">

                                                <label class="">Time To:</label>
                                                <input type="text" name="sleep_time_to" class="form-control"
                                                    placeholder="Enter time to">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Do you sleep during the day?</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="sleep_daytime"
                                                    value="yes">
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="sleep_daytime"
                                                    value="no">
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group row daytime-time">
                                            <p class="col-sm-2 ">If Yes,</p>
                                            <div class="col-sm-3">

                                                <label class="col-sm-2 ">Time From:</label>
                                                <input type="text" name="sleep_daytime_time_from" class="form-control"
                                                    placeholder="Enter time from">
                                            </div>
                                            <div class="col-sm-3">

                                                <label class="col-sm-2 ">Time To:</label>
                                                <input type="text" name="sleep_daytime_time_to" class="form-control"
                                                    placeholder="Enter time to">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Any difficulty in Initiation and Maintenance:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    name="sleep_difficulty_initiation">
                                                <label class="form-check-label">Initiation</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    name="sleep_difficulty_maintenance">
                                                <label class="form-check-label">Maintenance</label>
                                            </div>
                                        </div>
                                        <hr>

                                        <h4 class="fs-title">Stress</h4>

                                        <!-- Stress: Yes or No -->
                                        <div class="form-group">
                                            <label>Do you feel stressed?</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="stress"
                                                    value="yes">
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="stress"
                                                    value="no">
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>

                                        <!-- Stress Reason -->
                                        <div class="form-group">
                                            <label>Reasons for Stress:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="stress_reason"
                                                    value="work_related">
                                                <label class="form-check-label">Work Related</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="stress_reason"
                                                    value="financial">
                                                <label class="form-check-label">Financial</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="stress_reason"
                                                    value="inter_personal">
                                                <label class="form-check-label">Interpersonal</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="stress_reason"
                                                    value="health_related">
                                                <label class="form-check-label">Health Related</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="stress_reason"
                                                    value="education">
                                                <label class="form-check-label">Education</label>
                                            </div>
                                        </div>

                                        <!-- What worries you the most -->
                                        <div class="form-group">
                                            <label>What worries you the most?</label>
                                            <textarea class="form-control" name="stress_worries" rows="3"
                                                placeholder="Enter what worries you the most..."></textarea>
                                        </div>

                                        <hr>
                                    </div>

                                    <input type="button" name="previous"
                                        class="previous action-button-previous btn btn-secondary" value="Previous">
                                    <input type="button" name="next" class="next action-button btn btn-primary"
                                        value="Next Step">
                                </fieldset>


                                <fieldset>
                                    <div class="form-card">
                                        <h4 class="fs-title">Physical Assessment</h4>

                                        <div class="form-group">
                                            <label>Dosha:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="dosha" value="work_related">
                                                <label class="form-check-label">V(+/-)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="dosha" value="financial">
                                                <label class="form-check-label">P(+/-)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="dosha" value="inter_personal">
                                                <label class="form-check-label">K(+/-)</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="guna">Guna:</label>
                                            <input type="text" class="form-control" id="guna" name="guna">
                                        </div>

                                        <div class="form-group">
                                            <label for="sama_nirama">Sama/Nirama:</label>
                                            <input type="text" class="form-control" id="sama_nirama" name="sama_nirama">
                                        </div>

                                        <div class="form-group">
                                            <label>Marga:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="marga" value="Abyabatara">
                                                <label class="form-check-label">Abyabatara</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="marga" value="Madhyama">
                                                <label class="form-check-label">Madhyama</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="marga" value="Bahya">
                                                <label class="form-check-label">Bahya</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Agni:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="agni" value="Maadha">
                                                <label class="form-check-label">Maadha</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="agni" value="Teeksha">
                                                <label class="form-check-label">Teeksha</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="agni" value="Vishma">
                                                <label class="form-check-label">Vishma</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="agni" value="Sama">
                                                <label class="form-check-label">Sama</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Prakriti:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="prakriti" value="V">
                                                <label class="form-check-label">V</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="prakriti" value="P">
                                                <label class="form-check-label">P</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="prakriti" value="VP">
                                                <label class="form-check-label">VP</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="prakriti" value="Vk">
                                                <label class="form-check-label">Vk</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="prakriti" value="KP">
                                                <label class="form-check-label">KP</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="prakriti" value="VPK">
                                                <label class="form-check-label">VPK</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Vayas:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="vayas" value="Bala">
                                                <label class="form-check-label">Bala</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="vayas" value="Poorva">
                                                <label class="form-check-label">Poorva</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="vayas" value="Madhyama">
                                                <label class="form-check-label">Madhyama</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="vayas" value="Vruddha">
                                                <label class="form-check-label">Vruddha</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Balam:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="balam" value="L">
                                                <label class="form-check-label">L</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="balam" value="H">
                                                <label class="form-check-label">H</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="balam" value="M">
                                                <label class="form-check-label">M</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Satmyam:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="satmyam" value="Alpa">
                                                <label class="form-check-label">Alpa</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="satmyam" value="Madhyama">
                                                <label class="form-check-label">Madhyama</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="satmyam" value="Avara">
                                                <label class="form-check-label">Avara</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="roga_vinichaya">Roga Vinichaya:</label>
                                            <input type="text" class="form-control" id="roga_vinichaya" name="roga_vinichaya">
                                        </div>

                                        <div class="form-group">
                                            <label for="avastha">Avastha:</label>
                                            <input type="text" class="form-control" id="avastha" name="avastha">
                                        </div>

                                    </div>
                                    <input type="button" name="previous" class="previous action-button-previous"
                                        value="Previous" />
                                    <input type="button" name="make_payment" class="next action-button"
                                        value="Confirm" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title text-center">Success !</h2>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-3">
                                                <img src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                    class="fit-image">
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-7 text-center">
                                                <h5>Success</h5>
                                                <button type="button" class="btn btn-primary"> Go Back</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;

            $(".next").click(function() {

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            });

            $(".previous").click(function() {

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //Remove class active
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
            });

            $('.radio-group .radio').click(function() {
                $(this).parent().find('.radio').removeClass('selected');
                $(this).addClass('selected');
            });

            $(".submit").click(function() {
                return false;
            })

        });

        $(document).ready(function() {
            // Initialize Select2 on the element with class 'select2'
            $('.select2').select2({
                placeholder: "Select Patient",
                allowClear: true
            });
        });
    </script>


@endsection
