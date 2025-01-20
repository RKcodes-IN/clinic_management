@extends('layouts.user_type.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="">Create Invoice</div>

            <div class="col-md-12">
                <div class="row mb-3">
                    <!-- First Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Patient & Invoice Details</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" id="invoice_date"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="invoice_time">Invoice Time</label>
                                    <input type="time" name="invoice_time" value="{{ date('H:i') }}" id="invoice_time"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="patient_id">Patient Name</label>
                                    <select name="patient_id" id="patient_id" class="form-control" required>
                                        <option value="">Select Patient</option>
                                        @if ($patients instanceof \Illuminate\Database\Eloquent\Collection)
                                            <!-- If $patients is a collection, iterate over it -->
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                    {{ request()->query('patient_id') == $patient->id ? 'selected' : '' }}>
                                                    {{ $patient->name }}
                                                </option>
                                            @endforeach
                                        @elseif ($patients)
                                            <!-- If $patients is a single instance -->
                                            <option value="{{ $patients->id }}" selected>{{ $patients->name }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="bill_type">Bill Type</label>
                                    <select name="bill_type" id="bill_type" class="form-control" required>
                                        <option value="individual">Individual</option>
                                        <option value="business">Business</option>
                                    </select>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control">
                                </div> --}}

                                <div class="form-group">
                                    <label for="doctor_id">Select Doctor</label>
                                    <select name="doctor_id" id="doctor_id" class="form-control" required>
                                        <option value="">Select Doctor</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Card -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Invoice Summary</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="subtotal">Subtotal</label>
                                    <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Total Discount Amount</label>
                                    <input type="number" step="0.01" name="discount" id="discount"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="gst">GST (%)</label>
                                    <input type="number" step="0.01" name="gst" id="gst" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" step="0.01" name="total" id="total" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="payment_status">Payment Status</label>
                                    <select name="payment_status" id="payment_status" class="form-control">
                                        @foreach (\App\Models\Invoice::getPaymentStatusDropdown() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">


                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <!-- Pharmacy Items -->
                        <h5>Pharmacy Items</h5>
                        <form action="{{ route('invoice.store') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pharmacy-items-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Avl. Qty.</th>
                                            <th>Qty.</th>
                                            <th>Batch no.</th>
                                            <th>Exp. Date</th>
                                            <th>Price</th>
                                            <th>Discount(%)</th>
                                            <th>Dis. Amt.</th>
                                            <th>Total</th>
                                            <th>Desc.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="pharmacy[0][item_id]"
                                                    class="form-control pharmacy-item-select" required>
                                                    <option value="">Search and Select Item</option>
                                                    @foreach ($pharmacyStocks as $stock)
                                                        <option value="{{ $stock->id }}">
                                                            {{ $stock->item->name ?: $stock->item->item_code }}
                                                            ({{ $stock->item->item_code }})
                                                            ({{ \Carbon\Carbon::parse($stock->expiry_date)->format('d-M-Y') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="available-quantity">-</td>
                                            <td><input type="number" name="pharmacy[0][quantity]" class="form-control"
                                                    required></td>
                                            <td><input type="text" name="pharmacy[0][batch_number]"
                                                    class="form-control" required></td>
                                            <td><input type="date" name="pharmacy[0][expiry_date]"
                                                    class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="pharmacy[0][price]"
                                                    class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="pharmacy[0][add_dis_percent]"
                                                    class="form-control">
                                                <span class="text-danger" id="diserr_0"></span>
                                            </td>
                                            <td><input type="number" step="0.01" name="pharmacy[0][discount_amount]"
                                                    class="form-control"></td>
                                            <td><input type="number" step="0.01" name="pharmacy[0][total]"
                                                    class="form-control" readonly></td>
                                            <td><input type="text" name="pharmacy[0][description]"
                                                    class="form-control">
                                            </td>
                                            <td><button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" id="add-pharmacy-row" class="btn btn-secondary">Add More</button>
                            </div>

                            <!-- Lab Tests -->
                            <h5 class="mt-5">Lab Tests</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="lab-tests-table">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Discount(%)</th>
                                            <th>Dis. Amt.</th>
                                            <th>Total</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="labtests[0][item_id]"
                                                    class="form-control labtest-item-select" required>
                                                    <option value="">Search and Select Test</option>
                                                    @foreach ($labTestStocks as $stock)
                                                        <option value="{{ $stock->id }}">
                                                            {{ $stock->item->name ?: $stock->item->item_code }}
                                                            ({{ $stock->item->item_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="labtests[0][quantity]" class="form-control"
                                                    required></td>
                                            <td><input type="number" step="0.01" name="labtests[0][price]"
                                                    class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="labtests[0][add_dis_percent]"
                                                    class="form-control"></td>
                                            <td><input type="number" step="0.01" name="labtests[0][discount_amount]"
                                                    class="form-control" readonly></td>
                                            <td><input type="number" step="0.01" name="labtests[0][total]"
                                                    class="form-control" readonly></td>
                                            <td><input type="text" name="labtests[0][description]"
                                                    class="form-control"></td>
                                            <td><button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" id="add-labtest-row" class="btn btn-secondary">Add More</button>
                            </div>

                            <!-- Miscellaneous Items Section -->
                            <h5 class="mt-5">Miscellaneous Items</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="misc-items-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Discount(%)</th>
                                            <th>Dis. Amt.</th>
                                            <th>Total</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="misc[0][item_id]" class="form-control misc-item-select"
                                                    required>
                                                    <option value="">Search and Select Item</option>
                                                    @foreach ($miscellaneousStocks as $stock)
                                                        <option value="{{ $stock->id }}">
                                                            {{ $stock->item->name ?: $stock->item->item_code }}
                                                            ({{ $stock->item->item_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="misc[0][quantity]" class="form-control"
                                                    required></td>
                                            <td><input type="number" step="0.01" name="misc[0][price]"
                                                    class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="misc[0][add_dis_percent]"
                                                    class="form-control"></td>
                                            <td><input type="number" step="0.01" name="misc[0][discount_amount]"
                                                    class="form-control" readonly></td>
                                            <td><input type="number" step="0.01" name="misc[0][total]"
                                                    class="form-control" readonly></td>
                                            <td><input type="text" name="misc[0][description]" class="form-control">
                                            </td>
                                            <td><button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" id="add-misc-row" class="btn btn-secondary">Add More</button>
                            </div>

                    </div>
                </div>


                <button type="submit" class="btn btn-primary mt-3">Create Invoice</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <!-- Scripts -->

    <script>
        // Populate stock data for prices and details
        // Populate stock data for prices and details
        const pharmacyStocksData = {
            @foreach ($pharmacyStocks as $stock)
                '{{ $stock->id }}': {
                    price: {{ $stock->item_price ?? 0 }},
                    batch_no: '{{ $stock->batch_no ?? 0 }}',
                    expiry_date: '{{ \Carbon\Carbon::parse($stock->expiry_date)->format('Y-m-d') }}',
                },
            @endforeach
        };

        const labTestStocksData = {
            @foreach ($labTestStocks as $stock)
                '{{ $stock->id }}': {
                    price: {{ $stock->item_price ?? 0 }},
                },
            @endforeach
        };

        const miscStocksData = {
            @foreach ($miscellaneousStocks as $stock)
                '{{ $stock->id }}': {
                    price: {{ $stock->item_price ?? 0 }},
                },
            @endforeach
        };

        // Event listener for patient selection
        $('#patient_id').on('change', function() {
            const patientId = $(this).val();
            if (patientId) {
                $.ajax({
                    url: `/prescriptions/${patientId}`,
                    method: 'GET',
                    success: function(response) {
                        populatePrescriptions(response);
                    },
                    error: function() {
                        alert('Error fetching prescriptions.');
                    }
                });
            }
        });

        // Event listeners for stock selection
        $(document).on('change', '.pharmacy-item-select', function() {
            const stockId = $(this).val();
            const $row = $(this).closest('tr');
            if (stockId && pharmacyStocksData[stockId]) {
                const stockData = pharmacyStocksData[stockId];
                $row.find('input[name$="[price]"]').val(stockData.price);
                $row.find('input[name$="[batch_number]"]').val(stockData.batch_no);
                $row.find('input[name$="[expiry_date]"]').val(stockData.expiry_date);
            }
        });

        $(document).on('change', '.labtest-item-select', function() {
            const stockId = $(this).val();
            const $row = $(this).closest('tr');
            if (stockId && labTestStocksData[stockId]) {
                $row.find('input[name$="[price]"]').val(labTestStocksData[stockId].price);
            }
        });

        $(document).on('change', '.misc-item-select', function() {
            const stockId = $(this).val();
            const $row = $(this).closest('tr');
            if (stockId && miscStocksData[stockId]) {
                $row.find('input[name$="[price]"]').val(miscStocksData[stockId].price);
            }
        });

        function populatePrescriptions(data) {
            // Clear existing rows
            $('#pharmacy-items-table tbody').empty();
            $('#lab-tests-table tbody').empty();
            $('#misc-items-table tbody').empty();

            // Populate pharmacy prescriptions
            data.pharmacy.forEach((item, index) => {
                const newRow = `
        <tr>
            <td>
                <select name="pharmacy[${index}][item_id]" class="form-control pharmacy-item-select" required>
                    <option value="${item.item_id}" selected>${item.item_name}</option>
                </select>
            </td>
            <td class="available-quantity">${item.available_quantity}</td>
            <td><input type="number" name="pharmacy[${index}][quantity]" class="form-control" value="${item.quantity}" required></td>
            <td><input type="text" name="pharmacy[${index}][batch_number]" class="form-control" value="${item.batch_number}" required></td>
            <td><input type="date" name="pharmacy[${index}][expiry_date]" class="form-control" value="${item.expiry_date}" required></td>
            <td><input type="number" step="0.01" name="pharmacy[${index}][price]" class="form-control" value="${item.price}" required></td>
            <td><input type="number" step="0.01" name="pharmacy[${index}][add_dis_percent]" class="form-control" value="${item.discount_percent || 0}"></td>
            <td><input type="number" step="0.01" name="pharmacy[${index}][discount_amount]" class="form-control" value="${item.discount_amount || 0}" readonly></td>
            <td><input type="number" step="0.01" name="pharmacy[${index}][total]" class="form-control" value="${item.total}" readonly></td>
            <td><input type="text" name="pharmacy[${index}][description]" class="form-control" value="${item.description || ''}"></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>
        `;
                $('#pharmacy-items-table tbody').append(newRow);
            });

            // Populate lab test prescriptions
            data.lab.forEach((item, index) => {
                const newRow = `
        <tr>
            <td>
                <select name="labtests[${index}][item_id]" class="form-control labtest-item-select" required>
                    <option value="${item.item_id}" selected>${item.item_name}</option>
                </select>
            </td>
            <td><input type="number" name="labtests[${index}][quantity]" class="form-control" value="${item.quantity}" required></td>
            <td><input type="number" step="0.01" name="labtests[${index}][price]" class="form-control" value="${item.price}" required></td>
            <td><input type="number" step="0.01" name="labtests[${index}][add_dis_percent]" class="form-control" value="${item.discount_percent || 0}"></td>
            <td><input type="number" step="0.01" name="labtests[${index}][discount_amount]" class="form-control" value="${item.discount_amount || 0}" readonly></td>
            <td><input type="number" step="0.01" name="labtests[${index}][total]" class="form-control" value="${item.total}" readonly></td>
            <td><input type="text" name="labtests[${index}][description]" class="form-control" value="${item.description || ''}"></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>
        `;
                $('#lab-tests-table tbody').append(newRow);
            });

            // Populate miscellaneous items
            data.misc.forEach((item, index) => {
                const newRow = `
        <tr>
            <td>
                <select name="misc[${index}][item_id]" class="form-control misc-item-select" required>
                    <option value="${item.item_id}" selected>${item.item_name}</option>
                </select>
            </td>
            <td><input type="number" name="misc[${index}][quantity]" class="form-control" value="${item.quantity}" required></td>
            <td><input type="number" step="0.01" name="misc[${index}][price]" class="form-control" value="${item.price}" required></td>
            <td><input type="number" step="0.01" name="misc[${index}][add_dis_percent]" class="form-control" value="${item.discount_percent || 0}"></td>
            <td><input type="number" step="0.01" name="misc[${index}][discount_amount]" class="form-control" value="${item.discount_amount || 0}" readonly></td>
            <td><input type="number" step="0.01" name="misc[${index}][total]" class="form-control" value="${item.total}" readonly></td>
            <td><input type="text" name="misc[${index}][description]" class="form-control" value="${item.description || ''}"></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>
        `;
                $('#misc-items-table tbody').append(newRow);
            });

            initializeSelect2();
            bindCalculationEvents();
        }

        // Calculation functions
        function calculateRowTotal($row) {
            const quantity = parseFloat($row.find('input[name$="[quantity]"]').val()) || 0;
            const price = parseFloat($row.find('input[name$="[price]"]').val()) || 0;
            const discountPercent = parseFloat($row.find('input[name$="[add_dis_percent]"]').val()) || 0;

            const initialTotal = quantity * price;
            const discountAmount = (initialTotal * discountPercent / 100).toFixed(2);
            const total = (initialTotal - discountAmount).toFixed(2);

            $row.find('input[name$="[discount_amount]"]').val(discountAmount);
            $row.find('input[name$="[total]"]').val(total);
        }

        function calculateSubtotalAndDiscount() {
            let subtotal = 0;
            let totalDiscount = 0;

            $('input[name$="[total]"]').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });

            $('input[name$="[discount_amount]"]').each(function() {
                totalDiscount += parseFloat($(this).val()) || 0;
            });

            $('#subtotal').val(subtotal.toFixed(2));
            $('#discount').val(totalDiscount.toFixed(2));
            calculateTotal();
        }

        function calculateTotal() {
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            const discount = parseFloat($('#discount').val()) || 0;
            const gstPercentage = parseFloat($('#gst').val()) || 0;

            const gst = ((subtotal - discount) * gstPercentage) / 100;
            const total = (subtotal - discount + gst).toFixed(2);
            $('#total').val(total);
        }

        // Event bindings
        function bindCalculationEvents() {
            $(document).on('input',
                'input[name$="[quantity]"], input[name$="[price]"], input[name$="[add_dis_percent]"], #discount, #gst',
                function() {
                    const $row = $(this).closest('tr');
                    calculateRowTotal($row);
                    calculateSubtotalAndDiscount();
                }
            );

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                calculateSubtotalAndDiscount();
            });
        }

        // Dynamic row addition
        let pharmacyIndex = 1;
        $('#add-pharmacy-row').click(function() {
            const newRow = `
    <tr>
        <td>
            <select name="pharmacy[${pharmacyIndex}][item_id]" class="form-control pharmacy-item-select" required>
                <option value="">Search and Select Item</option>
                @foreach ($pharmacyStocks as $stock)
                    <option value="{{ $stock->id }}">
                        {{ $stock->item->name ?: $stock->item->item_code }}
                        ({{ $stock->item->item_code }})
                        ({{ \Carbon\Carbon::parse($stock->expiry_date)->format('d-M-Y') }})
                    </option>
                @endforeach
            </select>
        </td>
        <td class="available-quantity">-</td>
        <td><input type="number" name="pharmacy[${pharmacyIndex}][quantity]" class="form-control" required></td>
        <td><input type="text" name="pharmacy[${pharmacyIndex}][batch_number]" class="form-control" required></td>
        <td><input type="date" name="pharmacy[${pharmacyIndex}][expiry_date]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][price]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][add_dis_percent]" class="form-control"></td>
        <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][discount_amount]" class="form-control" readonly></td>
        <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][total]" class="form-control" readonly></td>
        <td><input type="text" name="pharmacy[${pharmacyIndex}][description]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
    </tr>
    `;
            $('#pharmacy-items-table tbody').append(newRow);
            pharmacyIndex++;
            initializeSelect2();
            bindCalculationEvents();
        });

        let labTestIndex = 1;
        $('#add-labtest-row').click(function() {
            const newRow = `
    <tr>
        <td>
            <select name="labtests[${labTestIndex}][item_id]" class="form-control labtest-item-select" required>
                <option value="">Search and Select Test</option>
                @foreach ($labTestStocks as $stock)
                    <option value="{{ $stock->id }}">
                        {{ $stock->item->name ?: $stock->item->item_code }}
                        ({{ $stock->item->item_code }})
                    </option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="labtests[${labTestIndex}][quantity]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="labtests[${labTestIndex}][price]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="labtests[${labTestIndex}][add_dis_percent]" class="form-control"></td>
        <td><input type="number" step="0.01" name="labtests[${labTestIndex}][discount_amount]" class="form-control" readonly></td>
        <td><input type="number" step="0.01" name="labtests[${labTestIndex}][total]" class="form-control" readonly></td>
        <td><input type="text" name="labtests[${labTestIndex}][description]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
    </tr>
    `;
            $('#lab-tests-table tbody').append(newRow);
            labTestIndex++;
            initializeSelect2();
            bindCalculationEvents();
        });

        let miscIndex = 1;
        $('#add-misc-row').click(function() {
            const newRow = `
    <tr>
        <td>
            <select name="misc[${miscIndex}][item_id]" class="form-control misc-item-select" required>
                <option value="">Search and Select Item</option>
                @foreach ($miscellaneousStocks as $stock)
                    <option value="{{ $stock->id }}">
                        {{ $stock->item->name ?: $stock->item->item_code }}
                        ({{ $stock->item->item_code }})
                    </option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="misc[${miscIndex}][quantity]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="misc[${miscIndex}][price]" class="form-control" required></td>
        <td><input type="number" step="0.01" name="misc[${miscIndex}][add_dis_percent]" class="form-control"></td>
        <td><input type="number" step="0.01" name="misc[${miscIndex}][discount_amount]" class="form-control" readonly></td>
        <td><input type="number" step="0.01" name="misc[${miscIndex}][total]" class="form-control" readonly></td>
        <td><input type="text" name="misc[${miscIndex}][description]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
    </tr>
    `;
            $('#misc-items-table tbody').append(newRow);
            miscIndex++;
            initializeSelect2();
            bindCalculationEvents();
        });

        function initializeSelect2() {
            $('.pharmacy-item-select, .labtest-item-select, .misc-item-select').select2({
                placeholder: "Search by name or item code",
                allowClear: true
            });
        }

        // Initial binding of calculation events
        bindCalculationEvents();
    </script>
@endsection
