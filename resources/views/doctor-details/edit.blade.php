@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Doctor</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('doctorDetail.update', $doctorDetail->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $doctorDetail->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $doctorDetail->user->email??"") }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" name="age" id="age" class="form-control" value="{{ old('age', $doctorDetail->age??"") }}" required>
                                @error('age')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $doctorDetail->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $doctorDetail->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $doctorDetail->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="education">Education</label>
                                <input type="text" name="education" id="education" class="form-control" value="{{ old('education', $doctorDetail->education) }}" required>
                                @error('education')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="specialty">Specialty</label>
                                <input type="text" name="specialty" id="specialty" class="form-control" value="{{ old('specialty', $doctorDetail->specialty) }}" required>
                                @error('specialty')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $doctorDetail->user->phone??"") }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="profile_image">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image" class="form-control-file">
                                @if ($doctorDetail->profile_image)
                                    <img src="{{ asset('storage/' . $doctorDetail->profile_image) }}" alt="Profile Image" class="img-thumbnail mt-2" width="100">
                                @endif
                                @error('profile_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" class="form-control">{{ old('address', $doctorDetail->address) }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Doctor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
