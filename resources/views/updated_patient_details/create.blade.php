@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h3 class="text-center">Create Patient Details</h3>
        <form action="{{ route('updated-patient-details.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="patient_id">Patient ID</label>
                <input type="text" class="form-control" name="patient_id" required>
            </div>
            <div class="form-group">
                <label for="name">Name (as per Aadhaar)</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="form-group">
                <label for="age">Age (as on)</label>
                <input type="number" class="form-control" name="age" required>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" name="dob">
            </div>

            <div class="form-group">
                <label for="email">Email Id</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="form-group">
                <label for="country_code">Country Code</label>
                <input type="text" class="form-control" name="country_code" required>
            </div>

            <div class="form-group">
                <label for="phone_number1">Phone Number</label>
                <input type="text" class="form-control" name="phone_number1" required>
            </div>

            <div class="form-group">
                <label for="alt_contact">Alternate Contact</label>
                <input type="text" class="form-control" name="alt_contact">
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" name="address"></textarea>
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" name="city" required>
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" name="country" value="India" required>
            </div>

            <div class="form-group">
                <label for="pincode">Pincode</label>
                <input type="text" class="form-control" name="pincode">
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
@endsection
