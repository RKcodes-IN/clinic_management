<div class="d-flex align-items-center">
    <span>
        <a href="{{ route('purchase-orders.show', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
            data-bs-original-title="View Purchase Order">
            <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
        </a>
    </span>
    <a href="{{ route('purchase_order.pdf', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
        data-bs-original-title="Download PDF">
        <i class="fas fa-file-pdf" aria-hidden="true"></i>
    </a>

    @php
        $purchaseOrder = App\Models\PurchaseOrder::find($id);
    @endphp

    @if ($purchaseOrder && $purchaseOrder->status != App\Models\PurchaseOrder::STATUS_RECIEVED)
        <a href="{{ route('editPurchaseOrderItems', ['purchase_order_id' => $id]) }}" class="mx-3"
            data-bs-toggle="tooltip" data-bs-original-title="Edit Purchase Order Items">
            <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
        </a>
    @endif
</div>
