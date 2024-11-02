@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-5">
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

                        <form action="{{ route('investigationreport.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

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

                            <!-- Report Name -->
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

                            <!-- File Upload -->
                            <div class="form-group">
                                <label for="file">Upload File</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="report_url"
                                        required>
                                    <label class="custom-file-label" for="file">Choose file...</label>
                                </div>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
