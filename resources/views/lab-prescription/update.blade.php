@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h4>Update Lab Prescription</h4>

        <form action="{{ route('labprescription.update', $labPrescription) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="sample_type_id" class="form-label">Sample Type</label>
                <select name="sample_type_id" id="sample_type_id" class="form-select">
                    @foreach ($sampleTypes as $sampleType)
                        <option value="{{ $sampleType->id }}"
                            {{ $labPrescription->sample_type_id == $sampleType->id ? 'selected' : '' }}>
                            {{ $sampleType->name }}
                        </option>
                    @endforeach
                </select>
                @error('sample_type_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sample_taken" id="sample_taken"
                        {{ $labPrescription->sample_taken || old('sample_taken') ? 'checked' : '' }}>
                    <label class="form-check-label" for="sample_taken">
                        Sample Taken
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="report_available" id="report_available"
                        {{ $labPrescription->report_available || old('report_available') ? 'checked' : '' }}
                        onchange="toggleReportUpload(this)">
                    <label class="form-check-label" for="report_available">
                        Report Available
                    </label>
                </div>
            </div>

            <div class="mb-3" id="report_upload_container" style="display: none;">
                <label for="report" class="form-label">Upload Report</label>
                <input type="file" class="form-control" name="report" id="report">
                @if ($labPrescription->report_path)
                    <div class="mt-2">
                        <a href="{{ Storage::url($labPrescription->report_path) }}" target="_blank">
                            View Current Report
                        </a>
                    </div>
                @endif
                @error('report')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('labprescription.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        function toggleReportUpload(checkbox) {
            document.getElementById('report_upload_container').style.display = checkbox.checked ? 'block' : 'none';
        }

        // Initialize visibility on page load
        window.onload = function() {
            const reportCheckbox = document.getElementById('report_available');
            toggleReportUpload(reportCheckbox);
        };
    </script>
@endsection
