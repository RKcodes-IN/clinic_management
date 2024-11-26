@extends('layouts.user_type.auth')

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
                                        <select name="patient_id" id="patient_id" class="form-control" required>
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
                                        <select name="report_type_id" id="report_type_id" class="form-control" required>
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
                                        <input type="date" name="report_date" id="report_date" class="form-control" required>
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
