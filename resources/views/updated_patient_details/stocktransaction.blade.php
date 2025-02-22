@extends('layouts.user_type.auth')

@section('content')
    <style>
        .form-container {
            margin: 0 auto 1rem;
        }

        .form-inline {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .form-inline .form-group {
            margin-bottom: 0;
        }
    </style>


    <style>
        .table-container {
            overflow-x: auto;
            /* Allow horizontal scrolling if necessary */
            margin: 0 auto;
            /* Center the table */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            /* Clean up border spacing */
        }

        .table th,
        .table td {
            padding: 8px 12px;
            /* Add proper padding */
            text-align: center;
            /* Center-align content */
            vertical-align: middle;
            /* Ensure text stays centered vertically */
        }

        .table th {
            font-size: 0.9rem;
            /* Slightly smaller font for headers */
            background-color: #f8f9fa;
            /* Light background for headers */
        }

        .table td {
            font-size: 0.875rem;
            /* Slightly smaller font for table cells */
            word-wrap: break-word;
            /* Break long text */
            overflow-wrap: break-word;
            /* Ensure compatibility with older browsers */
        }

        /* Responsive table adjustments */
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }

            .table {
                font-size: 0.8rem;
                /* Reduce font size on smaller screens */
            }
        }

        .select2-selection__rendered {
            max-height: 200px;
            /* Adjust this value to suit your design */
            overflow-y: scroll !important;
            /* Enable scrolling when content overflows */
            white-space: nowrap;
            /* Prevents wrapping of text */
            text-overflow: ellipsis;
            /* Adds ellipsis for overflow text */
        }



        .select2-selection {
            min-width: 200px;
            /* Adjust the minimum width as needed */
            max-width: 100%;
            /* Ensure it doesn't stretch too wide */
        }



        /* Limit height of the dropdown list */
        .select2-results {
            max-height: 300px;
            /* Set a max height for the dropdown list */
            overflow-y: auto;
            /* Enable scrolling inside the dropdown */
        }
    </style>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Pharmacy Transactions</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <form id="exportForm" method="POST" action="{{ route('stock.export') }}">
                                @csrf
                                <input type="hidden" name="item" value="{{ json_encode(request('item', [])) }}">
                                <input type="hidden" name="from_date" value="{{ request('from_date', '') }}">
                                <input type="hidden" name="to_date" value="{{ request('to_date', '') }}">
                                <button type="submit" class="btn btn-success">Export to Excel</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('stock.export.pdf') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item" value="{{ json_encode(request('item', [])) }}">
                                <input type="hidden" name="from_date" value="{{ request('from_date', '') }}">
                                <input type="hidden" name="to_date" value="{{ request('to_date', '') }}">
                                <button type="submit" class="btn btn-danger">Export to PDF</button>
                            </form>

                        </div>
                    </div>




                </div>
                <div class="form-container">
                    <div class="card-body">
                        <div class="form-container">
                            <form action="{{ route('stock.filter') }}" method="POST" class="form-inline">
                                @csrf
                                <div class="form-group">
                                    <label for="item" class="sr-only">Select Item</label>
                                    <select name="item[]" id="item" class="form-control select2" multiple>
                                        <option value="all">Select All</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}"
                                                @if (isset($selectedItem) && in_array($item->id, (array) $selectedItem)) selected @endif>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="from_date" class="sr-only">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        placeholder="From Date" value="{{ isset($fromDate) ? $fromDate : '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="to_date" class="sr-only">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        placeholder="To Date" value="{{ isset($toDate) ? $toDate : '' }}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="table-container">
                        @if (isset($transactions))
                            <table id="transactionsTable" class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th class="name-col">#</th>
                                        <th class="name-col">Item Name</th>
                                        <th class="code-col">Item Code</th>
                                        <th class="invoice-col">Invoice ID</th>
                                        {{-- <th class="invoice-col">Paitent</th> --}}
                                        <th class="po-col">PO ID</th>
                                        <th class="date-col">Date</th>
                                        <th>Transaction Type</th>
                                        <th class="date-col">Quantity</th>
                                        <th class="date-col">Item Price</th>
                                        <th class="date-col">Total Price</th>
                                        <th class="expiry-col">Expiry Date</th>
                                        <th class="balance-col">Balance Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sr = 1 @endphp
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $sr++ }}</td>
                                            <td>{{ $transaction->item->name }}</td>
                                            <td>{{ $transaction->item->item_code }}</td>
                                            <td>{{ $transaction->invoiceDetail->invoice->invoice_number ?? '' }}</td>
                                            {{-- <td>{{ $transaction->invoiceDetail->invoice->paitent->name ?? '' }}</td> --}}
                                            <td>{{ $transaction->purchase_order_id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-M-Y') }}
                                            </td>
                                            <td>{{ $transaction->status == 1 ? 'Incoming' : 'Outgoing' }}</td>
                                            <td>{{ $transaction->quantity }}</td>
                                            <td>{{ $transaction->item_price }}</td>
                                            <td>{{ $transaction->total_price }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->stock->expiry_date)->format('d-M-Y') }}
                                            </td>
                                            <td>{{ \App\Models\StockTransaction::getBalanceStockByDate($transaction->stock_id, $transaction->id) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No transactions found for the selected criteria.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTables on the transactions table
                $('#transactionsTable').DataTable({
                    "order": [
                        [1, "asc"]
                    ], // Default sorting by "Item Name" column
                    "paging": true, // Enable pagination
                    "searching": true, // Enable search box
                    "info": true, // Show table info
                    "autoWidth": false, // Disable automatic column width
                    "responsive": true // Enable responsive design
                });
            });
        </script>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Select2 with checkboxes
                $('#item').select2({
                    placeholder: 'Search and select items',
                    allowClear: true,
                    closeOnSelect: false, // Keep dropdown open for multiple selections
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text; // Display placeholder or default text
                        }
                        // Create a checkbox element
                        const isChecked = $('#item').val() && $('#item').val().includes(data.id.toString());
                        const checkbox =
                            `<input type="checkbox" ${isChecked ? 'checked' : ''} style="margin-right: 10px;">`;
                        return $(`<span>${checkbox} ${data.text}</span>`);
                    },
                    templateSelection: function(data, container) {
                        // If "Select All" is selected, show "All Items Selected"
                        const values = $('#item').val();
                        if (values && values.includes('all')) {
                            return 'All Items Selected';
                        }
                        return data.text;
                    }
                }).on('select2:select', function(e) {
                    const id = e.params.data.id;
                    if (id === 'all') {
                        // Select all options except "Select All"
                        $('#item option').prop('selected', true);
                        $('#item option[value="all"]').prop('selected', false);
                    }
                    $('#item').trigger('change');
                }).on('select2:unselect', function(e) {
                    const id = e.params.data.id;
                    if (id === 'all') {
                        // Unselect all options
                        $('#item option').prop('selected', false);
                    }
                    $('#item').trigger('change');
                });

                // Handle the "Select All" logic after form submission
                $('#item').on('change', function() {
                    const values = $(this).val();
                    if (values && values.includes('all')) {
                        $(this).val(['all']);
                    }
                });
            });
        </script>
    @endpush
