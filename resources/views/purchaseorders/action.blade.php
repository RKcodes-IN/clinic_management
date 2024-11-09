<a href="{{ route('items.edit', $id) }}" class="mx-3" data-bs-toggle="tooltip" title="Edit Item">
    <i class="fas fa-edit text-secondary" aria-hidden="true"></i>
</a>

<span>
    <form action="{{ route('items.destroy', $id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn p-0 border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this item?');">
            <i class="fas fa-trash text-secondary" aria-hidden="true"></i>
        </button>
    </form>
</span>
