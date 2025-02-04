@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Surgical Variable</h1>
        <form action="{{ route('surgical-variables.update', $surgicalVariable) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $surgicalVariable->name }}"
                    required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="1" {{ $surgicalVariable->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="2" {{ $surgicalVariable->status == 2 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Update</button>
        </form>
    </div>
@endsection
