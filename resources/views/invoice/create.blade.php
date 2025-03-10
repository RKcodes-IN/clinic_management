@extends('layouts.user_type.auth')

@section('content')

    <style>
        .select2 {
            width: 200.5px !important;
        }
    </style>
    <div class="row justify-content-center">
        @if (session('success'))
            <div class="alert alert-success">{{ session(key: 'success') }}</div>
        @endif
        <form action="{{ route('invoice.store') }}" method="POST">

            <div class="container">


                <div class="row mb-5">
                    <!-- First Card -->
                    @csrf
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
                                    <label for="patient_id">Patient Name</label> <br>
                                    <select name="patient_id" id="patient_id" class="form-control patient-select" required>
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
                                    <input type="number"  name="subtotal" id="subtotal" class="form-control"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Total Discount Amount</label>
                                    <input type="number"name="discount" id="discount"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="gst">GST (%)</label>
                                    <input type="number" name="gst" id="gst" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number"  name="total" id="total" class="form-control"
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Create Invoice</div>


                        <div class="card-body">


                            <!-- Pharmacy Items -->
                            <h5>Pharmacy Items</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="pharmacy-items-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Avl.<br> Qty.</th>
                                            <th>Qty.</th>
                                            <th>Batch <br> no.</th>
                                            <th>Exp.<br> Date</th>
                                            <th>Price</th>
                                            <th>Discount(%)</th>
                                            <th>Dis.<br> Amt.</th>
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
                                                    required>
                                                <span class="quantity-error"></span>
                                            </td>

                                            <td><input type="text" name="pharmacy[0][batch_number]"
                                                    class="form-control" required></td>
                                            <td><input type="date" name="pharmacy[0][expiry_date]"
                                                    class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="pharmacy[0][price]"
                                                    class="form-control" required></td>
                                            <td><input type="number" name="pharmacy[0][add_dis_percent]"
                                                    class="form-control">
                                                <span class="text-danger" id="diserr_0"></span>
                                            </td>
                                            <td><input type="number" step="1" name="pharmacy[0][discount_amount]"
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
                                            <td><input type="number" name="labtests[0][add_dis_percent]"
                                                    class="form-control">
                                                <span class="text-danger" id="diserr_0"></span>
                                            </td>
                                            <td><input type="number" step="1" name="labtests[0][discount_amount]"
                                                    class="form-control"></td>
                                            <td><input type="number" step="0.01" name="labtests[0][total]"
                                                    class="form-control" readonly></td>
                                            <td><input type="text" name="labtests[0][description]"
                                                    class="form-control">
                                            </td>
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
                                                    required>
                                            </td>
                                            <td><input type="number" step="0.01" name="misc[0][price]"
                                                    class="form-control" required></td>
                                            <td><input type="number" name="misc[0][add_dis_percent]"
                                                    class="form-control">
                                                <span class="text-danger" id="diserr_0"></span>
                                            </td>
                                            <td><input type="number" step="1" name="misc[0][discount_amount]"
                                                    class="form-control"></td>
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
                </div>
            </div>
        </form>

    </div>
    </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize Select2 for searchable dropdowns
            initializeSelect2();
            $('.patient-select').select2({
                placeholder: "Search and Select Patient",
                allowClear: false
            });
            // Populate pharmacy stock data for price and expiry date
            const pharmacyStocksData = {
                @foreach ($pharmacyStocks as $stock)
                    '{{ $stock->id }}': {
                        price: {{ $stock->item_price ?? 0 }},
                        batch_no: '{{ $stock->batch_no ?? 0 }}',
                        expiry_date: '{{ \Carbon\Carbon::parse($stock->expiry_date)->format('Y-m-d') }}',
                    },
                @endforeach
            };

            // Populate lab test stock data for price
            const labTestStocksData = {
                @foreach ($labTestStocks as $stock)
                    '{{ $stock->id }}': {
                        price: {{ $stock->item_price ?? 0 }},
                    },
                @endforeach
            };

            // Populate miscellaneous stock data for price
            const miscStocksData = {
                @foreach ($miscellaneousStocks as $stock)
                    '{{ $stock->id }}': {
                        price: {{ $stock->item_price ?? 0 }},
                    },
                @endforeach
            };

            // Function to calculate row total
            function calculateRowTotal($row) {
                const quantity = parseFloat($row.find('input[name$="[quantity]"]').val()) || 0;
                const price = parseFloat($row.find('input[name$="[price]"]').val()) || 0;
                const total = (quantity * price).toFixed(2);
                $row.find('input[name$="[total]"]').val(total);
            }

            function calculateDiscount($row) {
                const quantity = parseFloat($row.find('input[name$="[quantity]"]').val()) || 0;
                const price = parseFloat($row.find('input[name$="[price]"]').val()) || 0;
                const discountPercent = parseFloat($row.find('input[name$="[add_dis_percent]"]').val()) || 0;

                // Calculate the initial total
                const initialTotal = quantity * price;

                // Calculate the discount amount
                const discountAmount = (initialTotal * (discountPercent / 100)).toFixed(2);

                // Calculate the final total after discount
                const total = (initialTotal - discountAmount).toFixed(2);

                // Update the discount amount and total fields in the row
                // Assuming you have an input for discount amount
                $row.find('input[name$="[discount_amount]"]').val(
                    discountAmount); // Assuming you have an input for discount amount

                $row.find('input[name$="[total]"]').val(total);
            }



            // Function to calculate subtotal
            function calculateSubtotal() {
                let subtotal = 0;
                $('input[name$="[price]"]').each(function() {
                    let price = parseFloat($(this).val()) || 0;
                    let quantity = parseFloat($(this).closest('tr').find('input[name$="[quantity]"]')
                        .val()) || 0; // Assuming the quantity input is in the same row
                    subtotal += price * quantity;
                });
                $('#subtotal').val(subtotal.toFixed(2));
                calculateTotal(); // Recalculate the total when subtotal is updated
            }


            function calculateTotalamount() {
                let total = 0;
                $('input[name$="[total]"]').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total').val(total.toFixed(2));
                calculateTotal(); // Recalculate the total when subtotal is updated
            }

            function calculateTotalDisAmount() {
                let discountamount = 0;
                $('input[name$="[discount_amount]"]').each(function() {
                    discountamount += parseFloat($(this).val()) || 0;
                });
                $('#discount').val(discountamount.toFixed(2));
                calculateTotal(); // Recalculate the total when subtotal is updated
            }
            // Function to calculate total after discount and GST
            function calculateTotal() {
                const subtotal = parseFloat($('#subtotal').val()) || 0;
                const discountPercentage = parseFloat($('#discount').val()) || 0;
                const gstPercentage = parseFloat($('#gst').val()) || 0;

                const discount = discountPercentage;
                const gst = ((subtotal - discount) * gstPercentage) / 100;

                const total = (subtotal - discount + gst).toFixed(2);
                $('#total').val(total);
            }

            // Event listener for pharmacy item selection
            $(document).on('change', '.pharmacy-item-select', function() {
                const $row = $(this).closest('tr');
                const selectedStockId = $(this).val();
                const stockData = pharmacyStocksData[selectedStockId];


                if (stockData) {

                    $.ajax({
                        url: `/stocks/available/${selectedStockId}`,
                        method: 'GET',
                        success: function(response) {
                            // Update the Available Quantity column
                            $row.find('.available-quantity').text(response.available_stock);


                        },
                        error: function() {
                            // If error, show "-" as available stock
                            $row.find('.available-quantity').text('-');
                        }
                    });
                    // Populate price and expiry date
                    $row.find('input[name$="[batch_number]"]').val(stockData.batch_no);
                    $row.find('input[name$="[price]"]').val(stockData.price);
                    $row.find('input[name$="[expiry_date]"]').val(stockData.expiry_date);
                    calculateRowTotal($row); // Recalculate row total
                    calculateDiscount($row);
                    calculateTotalDisAmount() // Recalculate row total

                    calculateSubtotal();
                    calculateTotalamount();
                    // Recalculate subtotal
                }


            });

            // Event listener for lab test item selection
            $(document).on('change', '.labtest-item-select', function() {
                const $row = $(this).closest('tr');
                const selectedStockId = $(this).val();
                const stockData = labTestStocksData[selectedStockId];

                if (stockData) {
                    // Populate price
                    $row.find('input[name$="[price]"]').val(stockData.price);
                    calculateRowTotal($row);
                    calculateTotalDisAmount() // Recalculate row total
                    // Recalculate row total
                    calculateSubtotal();
                    calculateTotalamount();
                    // Recalculate subtotal
                }
            });

            // Event listener for miscellaneous item selection
            $(document).on('change', '.misc-item-select', function() {
                const $row = $(this).closest('tr');
                const selectedStockId = $(this).val();
                const stockData = miscStocksData[selectedStockId];

                if (stockData) {
                    // Populate price
                    $row.find('input[name$="[price]"]').val(stockData.price);
                    calculateRowTotal($row);
                    calculateTotalDisAmount() // Recalculate row total
                    // Recalculate row total
                    calculateSubtotal();
                    calculateTotalamount(); // Recalculate subtotal
                }
            });

            // Event listener for quantity and price changes
            $(document).on('input', 'input[name$="[add_dis_percent]"]', function() {
                const $row = $(this).closest('tr');
                const $itemSelect = $row.find('select[name$="[item_id]"]');
                const itemId = $itemSelect.val();
                const discountPercent = parseFloat($(this).val()) || 0;
                console.log($row);
                if (itemId) {
                    $.ajax({
                        url: `/stocks/get-discount/${itemId}`,
                        method: 'GET',
                        success: function(response) {
                            if (discountPercent > response.discount) {
                                $row.find('#diserr').text("Maximum discount for this item is " +
                                    response.discount + "%");
                                // Optionally, reset the discount to maximum allowed
                                // $row.find('input[name$="[add_dis_percent]"]').val(response.discount);
                            } else {
                                $row.find('#diserr').text(
                                    ''); // Clear error message if discount is valid
                            }
                        },
                        error: function() {
                            $row.find('#diserr').text('Error checking discount limit');
                        }
                    });
                }

                calculateDiscount($row);
                calculateTotalDisAmount();
                calculateSubtotal();
                calculateTotalamount();
            });

            // Update the discount error span IDs to be unique per row
            function updateDiscountErrorIds() {
                $('table tbody tr').each(function(index) {
                    $(this).find('#diserr').attr('id', 'diserr_' + index);
                });
            }

            $(document).on('input', 'input[name$="[quantity]"], input[name$="[price]"]', function() {
                const $row = $(this).closest('tr');

                validateQuantity($row);

                calculateRowTotal($row);
                calculateTotalDisAmount() // Recalculate row total

                calculateSubtotal();
                calculateTotalamount();
            });

            // Event listener for discount and GST changes
            $('#discount, #gst').on('input', function() {
                calculateTotal();
            });

            // Add More Button - Dynamically add rows for Pharmacy Items
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

                    <td><input type="number" name="pharmacy[${pharmacyIndex}][quantity]" class="form-control" required>
                                                <span class="quantity-error"></span>

                        </td>
                    <td><input type="text" name="pharmacy[${pharmacyIndex}][batch_number]" class="form-control" required></td>
                    <td><input type="date" name="pharmacy[${pharmacyIndex}][expiry_date]" class="form-control" required></td>
                    <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][price]" class="form-control" required></td>
                    <td><input type="number" name="pharmacy[${pharmacyIndex}][add_dis_percent]" class="form-control" >
                    <span class="text-danger" id="diserr_${pharmacyIndex}"></span>

                        </td>
                    <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][discount_amount]" class="form-control" readonly></td>
                    <td><input type="number" step="0.01" name="pharmacy[${pharmacyIndex}][total]" class="form-control" readonly></td>
                    <td><input type="text" name="pharmacy[${pharmacyIndex}][description]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                `;
                $('#pharmacy-items-table tbody').append(newRow);
                pharmacyIndex++;
                initializeSelect2(); // Re-initialize Select2 for the new row
            });

            // Add More Button - Dynamically add rows for Lab Tests
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
                     <td><input type="number" name="labtests[${pharmacyIndex}][add_dis_percent]" class="form-control" >
                    <span class="text-danger" id="diserr_${pharmacyIndex}"></span>

                        </td>
                    <td><input type="number" step="0.01" name="labtests[${pharmacyIndex}][discount_amount]" class="form-control" readonly></td>
                    <td><input type="number" step="0.01" name="labtests[${labTestIndex}][total]" class="form-control" readonly></td>
                    <td><input type="text" name="labtests[${labTestIndex}][description]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                `;
                $('#lab-tests-table tbody').append(newRow);
                labTestIndex++;
                initializeSelect2(); // Re-initialize Select2 for the new row
            });

            // Add More Button - Dynamically add rows for Miscellaneous Items
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
 <td><input type="number"  name="misc[${pharmacyIndex}][add_dis_percent]" class="form-control" >
                    <span class="text-danger" id="diserr_${pharmacyIndex}"></span>

                        </td>
                    <td><input type="number" step="0.01" name="misc[${pharmacyIndex}][discount_amount]" class="form-control" readonly></td>
                    <td><input type="number" step="0.01" name="misc[${miscIndex}][total]" class="form-control" readonly></td>
                    <td><input type="text" name="misc[${miscIndex}][description]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>
                `;
                $('#misc-items-table tbody').append(newRow);
                miscIndex++;
                initializeSelect2(); // Re-initialize Select2 for the new row
            });

            // Remove row functionality
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                calculateSubtotal(); // Recalculate subtotal when a row is removed
            });

            // Helper function to initialize Select2
            function initializeSelect2() {
                $('.pharmacy-item-select, .labtest-item-select, .misc-item-select').select2({
                    placeholder: "Search by name or item code",
                    allowClear: true
                });
            }
        });

        function validateQuantity($row) {
            const availableQty = parseFloat($row.find('.available-quantity').text()) || 0;
            const enteredQty = parseFloat($row.find('input[name$="[quantity]"]').val()) || 0;

            if (enteredQty > availableQty) {
                $row.find('.quantity-error').text('Entered quantity exceeds available stock.');
            } else {
                $row.find('.quantity-error').text('');
            }
        }
    </script>
@endsection
