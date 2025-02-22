@extends('layouts.user_type.auth')

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
        .word-wrap {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Appointments</span>
                <div class="d-flex align-items-center">
                    <input type="date" id="fromDate" value="{{ date('Y-m-d') }}" class="form-control me-2" placeholder="From Date">
                    <input type="date" id="toDate" value="{{ date('Y-m-d') }}" class="form-control me-2" placeholder="To Date">
                    <button id="applyFilters" class="btn btn-primary">Apply</button>
                    <button id="resetFilters" class="btn btn-secondary ms-2">Reset</button>
                </div>
            </div>
            <div class="table-responsive">
                <div class="card-body">
                    {!! $dataTable->table(['id' => 'appointmentdetail-table', 'class' => 'table table-bordered table-striped']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include necessary libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

    {!! $dataTable->scripts() !!}

    <script>
        // Use Yajra's generated initComplete callback instead of DOMContentLoaded
        window.dataTableInit = function() {
            // The table is already initialized by Yajra at this point
            let table = $('#appointmentdetail-table').DataTable();

            $('#applyFilters').on('click', function() {
                let fromDate = $('#fromDate').val();
                let toDate = $('#toDate').val();
                table.ajax.url("{{ route('appointments.index') }}?from_date=" + fromDate + "&to_date=" + toDate).load();
            });

            $('#resetFilters').on('click', function() {
                let today = new Date().toISOString().split('T')[0];
                $('#fromDate').val(today);
                $('#toDate').val(today);
                table.ajax.url("{{ route('appointments.index') }}").load();
            });
        };

        // Modify the DataTable initialization to include our callback
        $(document).ready(function() {
            let table = $('#appointmentdetail-table');
            table.on('init.dt', function() {
                window.dataTableInit();
            });
        });
    </script>
@endpush
