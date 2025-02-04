<a href="{{ route('surgical-variables.show', $id) }}" class="btn btn-info btn-sm">View</a>
{{--
<a href="{{ route('surgical-variables.edit', $id) }}" class="mx-3" data-bs-toggle="tooltip"
    data-bs-original-title="Edit user">
    <i class="fas fa-user-edit text-secondary" aria-hidden="true"></i>
</a> --}}

<span>

    <form action="{{ route('surgical-variables.destroy', $id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="border-0 background-transparent"
            onclick="return confirm('Are you sure you want to delete this uomtype?');"> <i
                class="cursor-pointer fas fa-trash text-secondary" aria-hidden="true"></i>
        </button>
    </form>


</span>
