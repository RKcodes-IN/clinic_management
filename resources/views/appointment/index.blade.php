@extends('layouts.user_type.auth')

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .word-wrap {
            white-space: normal;
            /* Allow text to wrap */
            word-wrap: break-word;
            /* Break words if needed */
            max-width: 200px;
            /* Set a max width for the column (adjust as necessary) */
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Appointments</span>
                <div class="d-flex align-items-center">
                    <input type="date" id="fromDate" value="{{ date('Y-m-d') }}" class="form-control me-2"
                        placeholder="From Date">
                    <input type="date" id="toDate" value="{{ date('Y-m-d') }}" class="form-control me-2"
                        placeholder="To Date">
                    <button id="applyFilters" class="btn btn-primary">Apply</button>
                    <button id="resetFilters" class="btn btn-secondary ms-2">Reset</button>
                </div>
            </div>
            <div class="table-responsive">
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module', 'responsive' => true]) }}

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>
@endpush
