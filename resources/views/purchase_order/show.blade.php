@extends('layouts.user_type.auth')
@push('styles')
@endpush
@section('content')
    <style>
        .large-swal-popup {
            width: 90% !important;
            max-width: 90% !important;
            height: 90% !important;
            max-height: 90% !important;
            padding: 20px !important;
        }
    </style>
    <div class="container">

        <h3>Purchase Order Details</h3>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Purchase Order #{{ $purchaseOrder->id }}</h5>
                <p><strong>Date:</strong> {{ $purchaseOrder->created_at->format('Y-m-d') }}</p>
                <p><strong>Status:</strong> {!! $purchaseOrder->getStatusLabel($purchaseOrder->status) !!}</p>

            </div>
        </div>

        <h4>Items</h4>
        <div class="card p-2">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Actions</th>

                            <th>#</th>
                            <th>Item <br>code</th>
                            <th>Item<br> Name</th>
                            <th>Qty.</th>
                            <th>Receive <br> Qty.</th>
                            <th>MRP</th>
                            <th>Purchase<br> Price</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->purchaseOrderItems as $item)
                            <tr>
                                <td>
                                    <button type="button" class="border-0 bg-none"
                                        onclick="showMarkReceiveModal(
                                        {{ $item->item->id ?? 0 }},
                                        {{ $item->quantity ?? 0 }},
                                        {{ $item->item_price ?? 0 }},
                                        {{ $item->id ?? 0 }}
                                    )">
                                        <i class="fa fa-arrows text-primary" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item->item_code ?? '' }}</td>
                                <td>{{ $item->item->poitem_name ?? '' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->received_quantity }}</td>
                                <td>₹{{ number_format($item->item_price, 2) }}</td>
                                <td>₹{{ number_format($item->purchase_price, 2) }}</td>
                                <td>₹{{ number_format($item->total_price, 2) }}</td>
                                <td>{!! $item->getStatusLabel($item->status) !!}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showMarkReceiveModal(itemId, maxQuantity, initialUnitPrice, $purchaseOrderItemId) {
            Swal.fire({
                title: 'Mark Received',
                html: `
        <div class="table-responsive">
            <table class="table" id="markReceiveTable">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Expiry <br> Date</th>

                        <th>Received <br> Quantity</th>
                        <th>Purchase <br> Price</th>
                        <th>MRP</th>
                        <th>Gross</th>

                        <th>Discount <br> Amount</th>
                        <th>Addl. Disc <br> Amount</th>
                        <th>Taxable <br> Amount</th>
                        <th>GST %</th>
                        <th>GST Amt.</th>
                        <th>Net Amount</th>

                        <th>Received <br> Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="batch[]" class="form-control batch" placeholder="Enter batch" required></td>
                        <td><input type="date" name="expiry_date[]" class="form-control" required></td>

                        <td><input type="number" name="received_quantity[]" class="form-control received-quantity" max="${maxQuantity}" placeholder="Enter quantity" required></td>
                        <td><input type="number" name="purchase_price[]" class="form-control purchase-price" placeholder="Enter purchase price" required></td>
                        <td><input type="number" name="unit_price[]" class="form-control unit-price" value="${initialUnitPrice}" placeholder="Enter unit price" required></td>
                        <td><input type="number" name="gross_amount[]" class="form-control" readonly></td>

                        <td><input type="number" name="discount_amount[]" class="form-control" placeholder="Discount Amount"></td>
                        <td><input type="number" name="additional_discount_amount[]" class="form-control" placeholder="Discount Amount"></td>
                        <td><input type="number" name="taxable_amount[]" class="form-control" readonly></td>
                        <td><input type="number" name="gst_percentage[]" class="form-control gst-percentage" placeholder="Enter GST percentage" required></td>
                        <td><input type="number" name="gst_amount[]" class="form-control gst-amount" readonly></td>
                        <td><input type="number" name="total_price[]" class="form-control total-price" readonly></td>

                        <td><input type="date" name="received_date[]" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm" id="addRow">Add Row</button>
        </div>
    `,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                customClass: {
                    popup: 'large-swal-popup'
                },
                preConfirm: () => {
                    const rows = document.querySelectorAll("#markReceiveTable tbody tr");
                    const data = [];
                    let isValid = true;

                    rows.forEach((row) => {
                        const receivedQuantity = parseFloat(row.querySelector(".received-quantity")
                            .value);
                        const purchasePrice = parseFloat(row.querySelector(".purchase-price").value);
                        const batch = row.querySelector(".batch").value;
                        const unitPrice = parseFloat(row.querySelector(".unit-price").value);
                        const discountAmount = parseFloat(row.querySelector(
                            '[name="discount_amount[]"]').value) || 0;
                        const additionalDiscountAmount = parseFloat(row.querySelector(
                            '[name="additional_discount_amount[]"]').value) || 0;
                        const gstPercentage = parseFloat(row.querySelector(".gst-percentage").value);
                        const expiryDate = row.querySelector('[name="expiry_date[]"]').value;
                        const receivedDate = row.querySelector('[name="received_date[]"]').value;

                        if (!receivedQuantity || receivedQuantity <= 0 || receivedQuantity >
                            maxQuantity) {
                            isValid = false;
                            Swal.showValidationMessage('Please enter a valid received quantity.');
                            return;
                        }

                        if (!purchasePrice || purchasePrice <= 0) {
                            isValid = false;
                            Swal.showValidationMessage('Please enter a valid purchase price.');
                            return;
                        }

                        if (!unitPrice || unitPrice <= 0) {
                            isValid = false;
                            Swal.showValidationMessage('Please enter a valid unit price.');
                            return;
                        }

                        if (isNaN(gstPercentage) || gstPercentage < 0) {
                            isValid = false;
                            Swal.showValidationMessage('Please enter a valid GST percentage.');
                            return;
                        }

                        if (!expiryDate) {
                            isValid = false;
                            Swal.showValidationMessage('Please select an expiry date.');
                            return;
                        }

                        if (!receivedDate) {
                            isValid = false;
                            Swal.showValidationMessage('Please select a received date.');
                            return;
                        }

                        // Calculate as per new logic:
                        const gross = receivedQuantity * purchasePrice;
                        const taxable = gross - (discountAmount + additionalDiscountAmount);
                        const gstAmount = taxable * gstPercentage / 100;
                        const netAmount = taxable + gstAmount;

                        data.push({
                            item_id: itemId,
                            "purchase_order_item_id": $purchaseOrderItemId,
                            received_quantity: receivedQuantity,
                            purchase_price: purchasePrice,
                            unit_price: unitPrice,
                            discount_amount: discountAmount,
                            additional_discount_amount: additionalDiscountAmount,
                            taxable_amount: taxable,
                            gst_percentage: gstPercentage,
                            gst_amount: gstAmount,
                            total_price: netAmount,
                            expiry_date: expiryDate,
                            batch: batch,
                            received_date: receivedDate
                        });
                    });

                    if (!isValid) return false;
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;

                    // Example AJAX request (replace URL with your endpoint)
                    fetch(`/purchase-orders/items/${itemId}/receive`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.success) {
                                Swal.fire('Success', 'Items marked as received!', 'success')
                                    .then(() => location.reload()); // Reload the page
                            } else {
                                Swal.fire('Error', 'Failed to mark items as received.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        });
                }
            });

            // Dynamic Row Functionality
            const tableBody = document.querySelector("#markReceiveTable tbody");

            document.getElementById("addRow").addEventListener("click", () => {
                const newRow = document.createElement("tr");
                newRow.innerHTML = `
         <td><input type="text" name="batch[]" class="form-control batch" placeholder="Enter batch" required></td>
                        <td><input type="date" name="expiry_date[]" class="form-control" required></td>

                        <td><input type="number" name="received_quantity[]" class="form-control received-quantity" max="${maxQuantity}" placeholder="Enter quantity" required></td>
                        <td><input type="number" name="purchase_price[]" class="form-control purchase-price" placeholder="Enter purchase price" required></td>
                        <td><input type="number" name="unit_price[]" class="form-control unit-price" value="${initialUnitPrice}" placeholder="Enter unit price" required></td>
                        <td><input type="number" name="gross_amount[]" class="form-control" readonly></td>

                        <td><input type="number" name="discount_amount[]" class="form-control" placeholder="Discount Amount"></td>
                        <td><input type="number" name="additional_discount_amount[]" class="form-control" placeholder="Discount Amount"></td>
                        <td><input type="number" name="taxable_amount[]" class="form-control" readonly></td>
                        <td><input type="number" name="gst_percentage[]" class="form-control gst-percentage" placeholder="Enter GST percentage" required></td>
                        <td><input type="number" name="gst_amount[]" class="form-control gst-amount" readonly></td>
                        <td><input type="number" name="total_price[]" class="form-control total-price" readonly></td>

                        <td><input type="date" name="received_date[]" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa-solid fa-minus"></i></button></td>
    `;
                tableBody.appendChild(newRow);
                attachEventListeners(newRow);
            });

            function attachEventListeners(row) {
                row.querySelector(".remove-row").addEventListener("click", () => row.remove());
                row.querySelector(".received-quantity").addEventListener("input", updateTotalPrice);
                row.querySelector(".purchase-price").addEventListener("input", updateTotalPrice);
                row.querySelector(".gst-percentage").addEventListener("input", updateTotalPrice);
                row.querySelector('[name="discount_amount[]"]').addEventListener("input", updateTotalPrice);
                row.querySelector('[name="additional_discount_amount[]"]').addEventListener("input", updateTotalPrice);
            }

            tableBody.querySelectorAll("tr").forEach((row) => attachEventListeners(row));

            function updateTotalPrice() {
                const rows = tableBody.querySelectorAll("tr");
                rows.forEach((row) => {
                    const receivedQuantity = parseFloat(row.querySelector(".received-quantity").value) || 0;
                    const purchasePrice = parseFloat(row.querySelector(".purchase-price").value) || 0;
                    const discountAmount = parseFloat(row.querySelector('[name="discount_amount[]"]').value) || 0;
                    const batch = row.querySelector(".batch").value;

                    const additionalDiscountAmount = parseFloat(row.querySelector(
                        '[name="additional_discount_amount[]"]').value) || 0;
                    const gstPercentage = parseFloat(row.querySelector(".gst-percentage").value) || 0;

                    const gross = receivedQuantity * purchasePrice;
                    const taxable = gross - (discountAmount + additionalDiscountAmount);
                    const gstAmount = taxable * gstPercentage / 100;
                    const netAmount = taxable + gstAmount;

                    // Update the calculated fields
                    row.querySelector('[name="gross_amount[]"]').value = gross.toFixed(2);
                    row.querySelector('[name="taxable_amount[]"]').value = taxable.toFixed(2);
                    row.querySelector('.gst-amount').value = gstAmount.toFixed(2);
                    row.querySelector('.total-price').value = netAmount.toFixed(2);
                });
            }
        }
    </script>
@endpush
