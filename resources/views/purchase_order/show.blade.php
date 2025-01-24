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
                            <th>#</th>
                            <th>Item <br>code</th>
                            <th>Item<br> Name</th>
                            <th>Qty.</th>
                            <th>Receive <br> Qty.</th>
                            <th>MRP</th>
                            <th>Purchase<br> Price</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrder->purchaseOrderItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item->item_code ?? '' }}</td>
                                <td>{{ $item->item->poitem_name ?? '' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->received_quantity }}</td>
                                <td>₹{{ number_format($item->item_price, 2) }}</td>
                                <td>₹{{ number_format($item->purchase_price, 2) }}</td>
                                <td>₹{{ number_format($item->total_price, 2) }}</td>
                                <td>{!! $item->getStatusLabel($item->status) !!}</td>
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
                title: 'Mark Receive',
                html: `
        <div class="table-responsive">
            <table class="table" id="markReceiveTable">
                <thead>
                    <tr>
                        <th>Received Quantity</th>
                        <th>Purchase Price</th>
                        <th>MRP</th>
                        <th>GST Percentage</th>
                        <th>GST Amount</th>
                        <th>Total Price</th>
                        <th>Expiry Date</th>
                        <th>Received Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="number" name="received_quantity[]" class="form-control received-quantity" max="${maxQuantity}" placeholder="Enter quantity" required></td>
                        <td><input type="number" name="purchase_price[]" class="form-control purchase-price" placeholder="Enter purchase price" required></td>
                        <td><input type="number" name="unit_price[]" class="form-control unit-price" value="${initialUnitPrice}" placeholder="Enter unit price" required></td>
                        <td><input type="number" name="gst_percentage[]" class="form-control gst-percentage" placeholder="Enter GST percentage" required></td>
                        <td><input type="number" name="gst_amount[]" class="form-control gst-amount" readonly></td>
                        <td><input type="number" name="total_price[]" class="form-control total-price" readonly></td>
                        <td><input type="date" name="expiry_date[]" class="form-control" required></td>
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
                        const receivedQuantity = row.querySelector(".received-quantity").value;
                        const purchasePrice = row.querySelector(".purchase-price").value;
                        const unitPrice = row.querySelector(".unit-price").value;
                        const gstPercentage = row.querySelector(".gst-percentage").value;
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

                        if (!gstPercentage || gstPercentage < 0) {
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

                        const gstAmount = (receivedQuantity * purchasePrice * gstPercentage) / 100;
                        const totalPrice = (receivedQuantity * purchasePrice) + gstAmount;

                        data.push({
                            item_id: itemId,
                            "purchase_order_item_id": $purchaseOrderItemId,
                            received_quantity: receivedQuantity,
                            purchase_price: purchasePrice,
                            unit_price: unitPrice,
                            gst_percentage: gstPercentage,
                            gst_amount: gstAmount,
                            total_price: totalPrice,
                            expiry_date: expiryDate,
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
        <td><input type="number" name="received_quantity[]" class="form-control received-quantity" max="${maxQuantity}" placeholder="Enter quantity" required></td>
        <td><input type="number" name="purchase_price[]" class="form-control purchase-price" placeholder="Enter purchase price" required></td>
        <td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="Enter unit price" required></td>
        <td><input type="number" name="gst_percentage[]" class="form-control gst-percentage" placeholder="Enter GST percentage" required></td>
        <td><input type="number" name="gst_amount[]" class="form-control gst-amount" readonly></td>
        <td><input type="number" name="total_price[]" class="form-control total-price" readonly></td>
        <td><input type="date" name="expiry_date[]" class="form-control" required></td>
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
            }

            tableBody.querySelectorAll("tr").forEach((row) => attachEventListeners(row));

            function updateTotalPrice() {
                const rows = tableBody.querySelectorAll("tr");
                rows.forEach((row) => {
                    const receivedQuantity = parseFloat(row.querySelector(".received-quantity").value) || 0;
                    const purchasePrice = parseFloat(row.querySelector(".purchase-price").value) || 0;
                    const gstPercentage = parseFloat(row.querySelector(".gst-percentage").value) || 0;
                    const gstAmount = (receivedQuantity * purchasePrice * gstPercentage) / 100;
                    const totalPrice = (receivedQuantity * purchasePrice) + gstAmount;

                    row.querySelector(".gst-amount").value = gstAmount.toFixed(2);
                    row.querySelector(".total-price").value = totalPrice.toFixed(2);
                });
            }
        }
    </script>
@endpush
