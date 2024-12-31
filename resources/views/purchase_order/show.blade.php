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
        <div class="card  p-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Recieve Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->purchaseOrderItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item->name ?? '' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->received_quantity }}</td>
                            <td>₹{{ number_format($item->item_price, 2) }}</td>
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
                                <th>Unit MRP</th>
                                <th>Total Price</th>
                                <th>Expiry Date</th>
                                <th>Received Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" name="received_quantity[]" class="form-control received-quantity" max="${maxQuantity}" placeholder="Enter quantity" required></td>
                                <td><input type="number" name="unit_price[]" class="form-control unit-price" value="${initialUnitPrice}" placeholder="Enter unit price" required></td>
                                <td><input type="number" name="total_price[]" class="form-control total-price" readonly></td>
                                <td><input type="date" name="expiry_date[]" class="form-control" required></td>
                                <td><input type="date" name="received_date[]" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
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
                        const unitPrice = row.querySelector(".unit-price").value;
                        const expiryDate = row.querySelector('[name="expiry_date[]"]').value;
                        const receivedDate = row.querySelector('[name="received_date[]"]').value;

                        if (!receivedQuantity || receivedQuantity <= 0 || receivedQuantity >
                            maxQuantity) {
                            isValid = false;
                            Swal.showValidationMessage('Please enter a valid received quantity.');
                            return;
                        }

                        if (!unitPrice || unitPrice <= 0) {
                            isValid = false;
                            Swal.showValidationMessage('Please enter a valid unit price.');
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

                        data.push({
                            item_id: itemId,
                            "purchase_order_item_id": $purchaseOrderItemId,
                            received_quantity: receivedQuantity,
                            unit_price: unitPrice,
                            total_price: receivedQuantity * unitPrice,
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
                <td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="Enter unit price" required></td>
                <td><input type="number" name="total_price[]" class="form-control total-price" readonly></td>
                <td><input type="date" name="expiry_date[]" class="form-control" required></td>
                <td><input type="date" name="received_date[]" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            `;
                tableBody.appendChild(newRow);
                attachEventListeners(newRow);
            });

            function attachEventListeners(row) {
                row.querySelector(".remove-row").addEventListener("click", () => row.remove());
                row.querySelector(".received-quantity").addEventListener("input", updateTotalPrice);
                row.querySelector(".unit-price").addEventListener("input", updateTotalPrice);
            }

            tableBody.querySelectorAll("tr").forEach((row) => attachEventListeners(row));

            function updateTotalPrice() {
                const rows = tableBody.querySelectorAll("tr");
                rows.forEach((row) => {
                    const receivedQuantity = parseFloat(row.querySelector(".received-quantity").value) || 0;
                    const unitPrice = parseFloat(row.querySelector(".unit-price").value) || 0;
                    row.querySelector(".total-price").value = (receivedQuantity * unitPrice).toFixed(2);
                });
            }
        }
    </script>
@endpush
