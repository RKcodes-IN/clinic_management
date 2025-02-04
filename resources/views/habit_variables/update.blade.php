@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Habit Variable</h1>

        <form action="{{ route('habit-variables.update', $habitVariable) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $habitVariable->name) }}" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" {{ $habitVariable->status == $value ? 'selected' : '' }}>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('habit-variables.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
