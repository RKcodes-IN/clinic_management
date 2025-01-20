<span>
    <a href="{{ route('purchase-orders.show', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
        data-bs-original-title="Edit user">
        <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
    </a>
</span>
<a href="{{ route('purchase_order.pdf', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
    data-bs-original-title="Edit user">
    <i class="fas fa-file-pdf" aria-hidden="true"></i>
</a>

<a href="{{ route('editPurchaseOrderItems', ['purchase_order_id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
    data-bs-original-title="Edit user">
    <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
</a>
