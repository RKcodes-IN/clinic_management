@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h4>Create New Habit Variable</h4>

        <form action="{{ route('habit-variables.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('habit-variables.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
