@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <!-- Patient Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Patient Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Name:</strong> {{ $labPrescription->patient->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Contact Number:</strong> {{ $labPrescription->patient->phone_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Address:</strong> {{ $labPrescription->patient->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <!-- Lab Prescriptions Table -->
                <h5>Update Lab Prescriptions for Date: {{ $labPrescriptions->first()->date ?? '' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('labprescription.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Sample Type</th>
                                <th>Sample Taken</th>
                                <th>Report Available</th>
                                <th>Value</th>
                                <th>Out of Range</th>
                                <th>Upload Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($labPrescriptions as $lp)
                                <tr>
                                    <td>{{ $lp->item->name }}</td>
                                    <td>
                                        <select name="lab_prescriptions[{{ $lp->id }}][sample_type_id]"
                                            id="sample_type_id_{{ $lp->id }}" class="form-select">
                                            @foreach ($sampleTypes as $sampleType)
                                                <option value="{{ $sampleType->id }}"
                                                    {{ $lp->sample_type_id == $sampleType->id ? 'selected' : '' }}>
                                                    {{ $sampleType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("lab_prescriptions.{$lp->id}.sample_type_id")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="lab_prescriptions[{{ $lp->id }}][sample_taken]"
                                                id="sample_taken_{{ $lp->id }}" value="1"
                                                {{ $lp->sample_taken ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sample_taken_{{ $lp->id }}">
                                                Sample Taken
                                            </label>
                                        </div>
                                        @error("lab_prescriptions.{$lp->id}.sample_taken")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="lab_prescriptions[{{ $lp->id }}][report_available]"
                                                id="report_available_{{ $lp->id }}" value="1"
                                                {{ $lp->report_available ? 'checked' : '' }}
                                                onchange="toggleReportUpload('{{ $lp->id }}')">
                                            <label class="form-check-label" for="report_available_{{ $lp->id }}">
                                                Report Available
                                            </label>
                                        </div>
                                        @error("lab_prescriptions.{$lp->id}.report_available")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="lab_prescriptions[{{ $lp->id }}][value]"
                                            id="value_{{ $lp->id }}" value="{{ $lp->value ?? '' }}">
                                        @error("lab_prescriptions.{$lp->id}.value")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="lab_prescriptions[{{ $lp->id }}][out_of_range]"
                                                id="out_of_range_{{ $lp->id }}" value="1"
                                                {{ $lp->out_of_range ? 'checked' : '' }}>
                                            <label class="form-check-label" for="out_of_range_{{ $lp->id }}">
                                                Out of Range
                                            </label>
                                        </div>
                                        @error("lab_prescriptions.{$lp->id}.out_of_range")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <div id="report_upload_{{ $lp->id }}"
                                            style="display: {{ $lp->report_available ? 'block' : 'none' }};">
                                            <input type="file" class="form-control"
                                                name="lab_prescriptions[{{ $lp->id }}][report]"
                                                id="report_{{ $lp->id }}">
                                            @if ($lp->report_path)
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($lp->report_path) }}" target="_blank">
                                                        View Current Report
                                                    </a>
                                                </div>
                                            @endif
                                            @error("lab_prescriptions.{$lp->id}.report")
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update All</button>
                        <a href="{{ route('labprescription.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleReportUpload(lpId) {
            const container = document.getElementById(`report_upload_${lpId}`);
            const checkbox = document.getElementById(`report_available_${lpId}`);
            container.style.display = checkbox.checked ? 'block' : 'none';
        }

        // Initialize visibility on page load
        window.onload = function() {
            @foreach ($labPrescriptions as $lp)
                toggleReportUpload('{{ $lp->id }}');
            @endforeach
        };
    </script>
@endsection
