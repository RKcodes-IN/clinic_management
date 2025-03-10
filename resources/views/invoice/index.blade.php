@extends('layouts.user_type.auth')

@section('content')
    <style>
        .filter-form .form-control,
        .filter-form .form-select {
            max-width: 200px;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Invoices</span>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form id="filterForm" class="row g-3 mb-3 filter-form">
                    <div class="col-md-2">
                        <label for="dateFrom" class="form-label">Date From</label>
                        <input type="date" id="dateFrom" name="dateFrom" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="dateTo" class="form-label">Date To</label>
                        <input type="date" id="dateTo" name="dateTo" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-2">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter mobile">
                    </div>
                    <div class="col-md-2">
                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                        <input type="text" id="invoiceNumber" name="invoiceNumber" class="form-control"
                            placeholder="Invoice #">
                    </div>
                    <div class="col-md-2">
                        <label for="paymentStatus" class="form-label">Payment Status</label>
                        <select id="paymentStatus" name="paymentStatus" class="form-select">
                            <option value="">All</option>
                            <option value="0">Pending</option>
                            <option value="1">Paid</option>
                            <option value="4">Partial Payment</option>
                            <option value="3">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
                        <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

                <!-- DataTable -->
                <div class="table-responsive">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module', 'responsive' => true]) }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script type="text/javascript">
        // Use the table's instance name (here we assume it's 'invoice-table') that matches your DataTable service.
        $(document).ready(function() {
            $('#invoice-table').on('init.dt', function() {
                // At this point, the DataTable should be initialized.
                if (window.LaravelDataTables && window.LaravelDataTables["invoice-table"]) {
                    var dtInstance = window.LaravelDataTables["invoice-table"];
                    dtInstance.on('preXhr.dt', function(e, settings, data) {
                        data.dateFrom = $('#dateFrom').val();
                        data.dateTo = $('#dateTo').val();
                        data.name = $('#name').val();
                        data.phone_number = $('#mobile').val();
                        data.invoiceNumber = $('#invoiceNumber').val();
                        data.paymentStatus = $('#paymentStatus').val();
                    });

                    $('#filterButton').on('click', function() {
                        dtInstance.ajax.reload();
                    });

                    $('#resetButton').on('click', function() {
                        $('#filterForm')[0].reset();
                        dtInstance.ajax.reload();
                    });
                } else {
                    console.error('DataTable "invoice-table" is not initialized.');
                }
            });
        });
    </script>
@endpush
