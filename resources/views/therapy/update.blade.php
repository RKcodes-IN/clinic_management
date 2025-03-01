@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <!-- Patient Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Patient Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Name:</strong> {{ $therapy->patient->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Contact Number:</strong> {{ $therapy->patient->phone_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Address:</strong> {{ $therapy->patient->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <!-- Therapies Table -->
                <h5>Update Therapies for Date: {{ $therapies->first()->date ?? '' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('therapy.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Sub Category</th>
                                <th>Material</th>
                                <th>Application Area</th>
                                <th>Time</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($therapies as $therapy)
                                <tr>
                                    <td>{{ $therapy->item->name ?? '' }}</td>
                                    <td>
                                        {{ $therapy->sub_category }}

                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="therapies[{{ $therapy->id }}][material]"
                                            value="{{ $therapy->material ?? '' }}">
                                        @error("therapies.{$therapy->id}.material")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="therapies[{{ $therapy->id }}][application_area]"
                                            value="{{ $therapy->application_area ?? '' }}">
                                        @error("therapies.{$therapy->id}.application_area")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col">
                                                <input type="time" class="form-control"
                                                    name="therapies[{{ $therapy->id }}][time_from]"
                                                    value="{{ $therapy->time_from ?? '' }}">
                                            </div>
                                            <div class="col">
                                                <input type="time" class="form-control"
                                                    name="therapies[{{ $therapy->id }}][time_to]"
                                                    value="{{ $therapy->time_to ?? '' }}">
                                            </div>
                                        </div>
                                        @error("therapies.{$therapy->id}.time_from")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error("therapies.{$therapy->id}.time_to")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="therapies[{{ $therapy->id }}][completed]" value="1"
                                                {{ $therapy->completed ? 'checked' : '' }}>
                                            <label class="form-check-label">Completed</label>
                                        </div>
                                        @error("therapies.{$therapy->id}.completed")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update All</button>
                        <a href="{{ route('therapy.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
