@extends('layouts.user_type.auth')

@section('content')
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading"></h4>
            <p>{{ session('success') }}</p>
            <p class="mb-0"></p>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading"></h4>
            <p>{{ session('error') }}</p>
            <p class="mb-0"></p>
        </div>
    @endif

    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h6>Create Return Request</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('return-request.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="items" id="items_data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_search">Search Invoice</label>
                                        <select class="form-control" id="invoice_search" name="invoice_search">
                                            <option></option>
                                        </select>
                                        <input type="hidden" name="invoice_id" id="invoice_id">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Patient Name</label>
                                        <input type="text" class="form-control" id="patient_name" readonly>
                                        <input type="hidden" name="patient_id" id="patient_id">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0" id="items_table">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Item</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Original Quantity</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Return Quantity</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Unit Price</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Total Amount</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Reason for Return</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Items will be populated here via JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="return_status">Return Status</label>
                                        <select class="form-control" id="return_status" name="return_status" required>
                                            <option value="">Select Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="return_date">Return Date</label>
                                        <input type="date" class="form-control" id="return_date" name="return_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="total_amount"
                                            name="total_amount" value="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" onclick="prepareFormData()">Submit Return
                                        Request</button>
                                    <a href="{{ route('return-request.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
            rel="stylesheet" />

        <script>
            $(document).ready(function() {
                // Initialize select2 for invoice search
                $('#invoice_search').select2({
                    theme: 'bootstrap-5',
                    ajax: {
                        url: '{{ route('return-request.search') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            console.log('Search Results:', data); // Debug log
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search by invoice number...',
                    minimumInputLength: 1,
                    width: '100%',
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                // Handle invoice selection
                $('#invoice_search').on('select2:select', function(e) {
                    console.log('Selection Event Data:', e.params.data); // Debug log
                    var data = e.params.data;

                    // Debug: Log the raw data
                    console.log('Raw Selection Data:', {
                        id: data.id,
                        patient_id: data.patient_id,
                        patient_name: data.patient_name
                    });

                    // Set the values
                    $('#invoice_id').val(data.id);
                    $('#patient_id').val(data.patient_id);
                    $('#patient_name').val(data.patient_name);

                    // Debug: Verify the values were set
                    console.log('Form Values After Setting:', {
                        invoice_id: $('#invoice_id').val(),
                        patient_id: $('#patient_id').val(),
                        patient_name: $('#patient_name').val()
                    });

                    // Create a new option and append it to the select if it doesn't exist
                    if (!$('#invoice_search').find("option[value='" + data.id + "']").length) {
                        var newOption = new Option(data.text, data.id, true, true);
                        $('#invoice_search').append(newOption);
                    }

                    // Load invoice items
                    loadInvoiceItems(data.id);
                });

                function loadInvoiceItems(invoiceId) {
                    $.ajax({
                        url: '{{ route('return-request.items') }}',
                        data: {
                            invoice_id: invoiceId
                        },
                        success: function(response) {
                            var tbody = $('#items_table tbody');
                            tbody.empty();

                            response.items.forEach(function(item) {
                                var row = `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td>${item.quantity}</td>
                                        <td>
                                            <input type="hidden" class="item-id" value="${item.id}">
                                            <input type="hidden" class="invoice-detail-id" value="${item.invoice_detail_id}">
                                            <input type="number" class="form-control return-quantity"
                                                   max="${item.quantity}" min="0"
                                                   data-unit-price="${item.unit_price}"
                                                   data-original-quantity="${item.quantity}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control unit-price"
                                                   value="${item.unit_price}"
                                                   data-original-price="${item.original_price}"
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control item-total"
                                                   value="0.00"
                                                   step="0.01">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control return-reason"
                                                   placeholder="Enter reason for return">
                                        </td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });

                            // Initialize quantity change handlers
                            $('.return-quantity').on('change', calculateTotal);
                        }
                    });
                }

                function calculateItemTotal(row) {
                    var quantity = parseFloat(row.find('.return-quantity').val()) || 0;
                    var unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
                    var total = quantity * unitPrice;
                    row.find('.item-total').val(total.toFixed(2));
                    updateGrandTotal();
                }

                function updateGrandTotal() {
                    var grandTotal = 0;
                    $('.item-total').each(function() {
                        grandTotal += parseFloat($(this).val()) || 0;
                    });
                    $('#total_amount').val(grandTotal.toFixed(2));
                }

                // Handle unit price input
                $(document).on('input', '.unit-price', function() {
                    calculateItemTotal($(this).closest('tr'));
                });

                // Handle quantity input
                $(document).on('input', '.return-quantity', function() {
                    calculateItemTotal($(this).closest('tr'));
                });

                // Handle item total input
                $(document).on('input', '.item-total', function() {
                    updateGrandTotal();
                });

                // Handle grand total input
                $(document).on('input', '#total_amount', function() {
                    var newTotal = parseFloat($(this).val()) || 0;
                    var currentTotal = 0;

                    // Calculate current total from items
                    $('.item-total').each(function() {
                        currentTotal += parseFloat($(this).val()) || 0;
                    });

                    // If total was manually changed, adjust the last item's total
                    if (newTotal !== currentTotal && newTotal > 0) {
                        var lastItem = $('.item-total').last();
                        lastItem.val(newTotal.toFixed(2));
                    }
                });

                function prepareFormData() {
                    // Validate required fields
                    if (!$('#invoice_id').val()) {
                        alert('Please select an invoice');
                        return false;
                    }
                    if (!$('#patient_id').val()) {
                        alert('Patient information is missing');
                        return false;
                    }
                    if (!$('#return_status').val()) {
                        alert('Please select a return status');
                        return false;
                    }
                    if (!$('#return_date').val()) {
                        alert('Please select a return date');
                        return false;
                    }

                    var items = [];
                    $('#items_table tbody tr').each(function() {
                        var row = $(this);
                        var quantity = parseFloat(row.find('.return-quantity').val()) || 0;
                        if (quantity > 0) {
                            items.push({
                                item_id: row.find('.item-id').val(),
                                invoice_detail_id: row.find('.invoice-detail-id').val(),
                                quantity: quantity,
                                unit_price: parseFloat(row.find('.unit-price').val()) || 0,
                                total_amount: parseFloat(row.find('.item-total').val()) || 0,
                                reason: row.find('.return-reason').val()
                            });
                        }
                    });

                    if (items.length === 0) {
                        alert('Please add at least one item to return');
                        return false;
                    }

                    $('#items_data').val(JSON.stringify(items));
                    return true;
                }

                // Add debug for form submission
                $('form').on('submit', function(e) {
                    console.log('Form Values Before Submission:', {
                        invoice_id: $('#invoice_id').val(),
                        patient_id: $('#patient_id').val(),
                        patient_name: $('#patient_name').val()
                    });

                    if (!prepareFormData()) {
                        e.preventDefault();
                    }
                });

                // Add change event listener for patient_id field
                $('#patient_id').on('change', function() {
                    console.log('Patient ID Changed:', $(this).val());
                });
            });
        </script>
    @endpush
@endsection
