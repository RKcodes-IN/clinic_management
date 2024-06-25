@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Role</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Enter role name" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Permissions</label><br>
                                <div class="row">
                                    @foreach ($permissions as $model_name => $groupedPermissions)
                                        <div class="col-md-3">
                                            <h5>{{ $model_name }}</h5>
                                            <div class="form-check">
                                                @foreach ($groupedPermissions as $permission)
                                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}">
                                                    <label class="form-check-label">{{ $permission->name }}</label><br>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('permissions')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
