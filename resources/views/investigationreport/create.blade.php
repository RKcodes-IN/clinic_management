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

                                    <!-- Report Date -->
                                    <div class="form-group">
                                        <label for="report_date">Report Date</label>
                                        <input type="date" value="{{ date('Y-m-d') }}" name="report_date" id="report_date" class="form-control" required>
                                        @error('report_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
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

                            <!-- Dynamic Investigation Parameters -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label>Investigation Parameters</label>
                                    <table class="table table-bordered" id="parameters-table">
                                        <thead>
                                            <tr>
                                                <th>Report Type</th>
                                                <th>Value</th>
                                                <th>Out of Range</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 3; $i++)
                                                <tr>
                                                    <td>
                                                        <select name="report_types[]" class="form-control select2" required>
                                                            <option value="" disabled selected>Select Report Type</option>
                                                            @foreach ($investigationReportType as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="values[]" class="form-control" placeholder="Enter value"></td>
                                                    <td class="text-center"><input type="checkbox" name="out_of_range[]"></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-row">Add More</button>
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
                allowClear: true,
                width: '100%'
            });

            // Add new row for parameters
            $('#add-row').on('click', function () {
                let newRow = `
                    <tr>
                        <td>
                            <select name="report_types[]" class="form-control select2" required>
                                <option value="" disabled selected>Select Report Type</option>
                                @foreach ($investigationReportType as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="values[]" class="form-control" placeholder="Enter value"></td>
                        <td class="text-center"><input type="checkbox" name="out_of_range[]"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                        </td>
                    </tr>`;
                $('#parameters-table tbody').append(newRow);
                $('.select2').select2({
                    placeholder: "Search...",
                    allowClear: true,
                    width: '100%'
                });
            });

            // Remove row
            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
            });
        });
    </script>
