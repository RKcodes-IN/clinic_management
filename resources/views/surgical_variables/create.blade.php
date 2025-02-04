@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Create Surgical Variable</h1>
        <form action="{{ route('surgical-variables.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>
    </div>
@endsection
