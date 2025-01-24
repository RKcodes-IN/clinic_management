<a href="{{ route('items.edit', $id) }}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
    <i class="fas fa-eye text-secondary" aria-hidden="true"></i>
</a>
<a href="{{ route('labprescription.edit', parameters: $id) }}" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
    <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
</a>

<span>

    <form action="#" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="border-0 background-transparent"
            onclick="return confirm('Are you sure you want to delete this category?');"> <i
                class="cursor-pointer fas fa-trash text-secondary" aria-hidden="true"></i>
        </button>
    </form>


</span>
