<a href="{{ route('paitent.show', ['id' => $id]) }}" class="" data-bs-toggle="tooltip"
    data-bs-original-title="view appointment">
    <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
</a>
<a href="{{ route('paitent.edit', ['id' => $id]) }}" class="mx-3" data-bs-toggle="tooltip"
    data-bs-original-title="Edit user">
    <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
</a>
<span>
    <i class="cursor-pointer fas fa-trash text-secondary" aria-hidden="true"></i>
</span>
