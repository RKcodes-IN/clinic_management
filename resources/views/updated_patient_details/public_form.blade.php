@extends('layouts.user_type.guest')

@section('content')

    <div class="container mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Header Section with Logo and Institute Details -->
        <div class="row align-items-center mb-4">
            <div class="col-md-2 text-center">
                <img src="https://ik.imagekit.io/phbranchi/logo-ct_dMECUkXSB.png?updatedAt=1734284741500" alt="Logo" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="col-md-10">
                <h3 class="mb-0">S.I.V.A.S HEALTH & RESEARCH INSTITUTE</h3>
                <p class="mb-0">Center for Health by Integration of Modern Medicine, Ayurveda & Yoga</p>
                <p class="mb-0">Center for Eye Diseases</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Update Your Details</h4>
                <!-- Make sure the form action points to your public store route -->
                <form action="{{ route('public.patient.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($patient_id))
                        <input type="hidden" name="patient_id" value="{{ $patient_id }}">
                    @endif

                    <!-- Personal Information -->
                    <div class="form-group">
                        <label for="name">Name (as per Aadhaar)</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter your name"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="age">Age (as on)</label>
                            <input type="number" class="form-control" name="age" placeholder="Enter your age"
                                value="{{ old('age') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email Id</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email"
                                value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="country_code">Country Code</label>
                            <input type="text" class="form-control" name="country_code" placeholder="+91"
                                value="{{ old('country_code') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="phone_number1">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number1" placeholder="Enter phone number"
                                value="{{ old('phone_number1') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="alt_contact">Alternate Contact</label>
                            <input type="text" class="form-control" name="alt_contact" placeholder="Alternate contact"
                                value="{{ old('alt_contact') }}">
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" name="address" placeholder="Enter your address">{{ old('address') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" placeholder="Enter city"
                                value="{{ old('city') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" name="country" placeholder="Country"
                                value="{{ old('country', 'India') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pincode">Pincode</label>
                            <input type="text" class="form-control" name="pincode" placeholder="Enter pincode"
                                value="{{ old('pincode') }}">
                        </div>
                    </div>

                    <!-- Image Upload (optional) -->
                    <div class="form-group">
                        <label for="image">Image (optional)</label>
                        <input type="file" class="form-control-file" name="image">
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
