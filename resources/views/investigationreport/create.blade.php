@extends('layouts.user_type.auth')

@section('styles')
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Upload Investigation Report</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('investigationreport.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Patient Selection -->
                                    <div class="form-group">
                                        <label for="patient_id">Select Patient</label>
                                        <select name="patient_id" id="patient_id" class="form-control select2" required>
                                            <option value="" disabled selected>Select a patient</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('patient_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Report Type -->
                                    <div class="form-group">
                                        <label for="report_type_id">Select Report Type</label>
                                        <select name="report_type_id" id="report_type_id" class="form-control select2" required>
                                            <option value="" disabled selected>Select Investigation Report Type</option>
                                            @foreach ($investigationReportType as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('report_type_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- Report Date -->
                                    <div class="form-group">
                                        <label for="report_date">Report Date</label>
                                        <input type="date" value="{{ date('Y-m-d') }}" name="report_date" id="report_date" class="form-control" required>
                                        @error('report_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- File Upload -->
                                    <div class="form-group">
                                        <label for="file">Upload File</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="report_url" required>
                                            <label class="custom-file-label" for="file">Choose file...</label>
                                        </div>
                                        @error('file')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Investigation Parameters -->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Value</th>
                                                <th>Out of Range</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Transferrin Saturation</td>
                                                <td>17.6</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="transferrin_sat" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>TSH</td>
                                                <td>2.25</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="tsh" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Vitamin B12</td>
                                                <td>291</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="vitamin_b" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Sodium</td>
                                                <td>143</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="sodium" value="1">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 for searchable dropdowns
            $('.select2').select2({
                placeholder: "Search...",
                allowClear: true, // Adds a clear button
                width: '100%' // Ensures the dropdown matches the input width
            });
        });
    </script>
