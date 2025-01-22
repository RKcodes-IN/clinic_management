@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Update Source Company</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('source-company.update', $sourceCompany->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $sourceCompany->name) }}" >
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" class="form-control" >{{ old('address', $sourceCompany->address) }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $sourceCompany->email) }}" >
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone_one">Phone One</label>
                                <input type="text" name="phone_one" id="phone_one" class="form-control"
                                    value="{{ old('phone_one', $sourceCompany->phone_one) }}" >
                                @error('phone_one')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone_two">Phone Two</label>
                                <input type="text" name="phone_two" id="phone_two" class="form-control"
                                    value="{{ old('phone_two', $sourceCompany->phone_two) }}">
                                @error('phone_two')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gst_no">GST Number</label>
                                <input type="text" name="gst_no" id="gst_no" class="form-control"
                                    value="{{ old('gst_no', $sourceCompany->gst_no) }}" >
                                @error('gst_no')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact_person">Contact Person</label>
                                <input type="text" name="contact_person" id="contact_person" class="form-control"
                                    value="{{ old('contact_person', $sourceCompany->contact_person) }}" >
                                @error('contact_person')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" >
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <button type="submit" class="btn btn-primary">Update Source Company</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
