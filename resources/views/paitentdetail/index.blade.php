@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Patient's Lists</span>
                @can('create paitent')
                    <a href="{{ route('patient.create') }}" class="btn btn-primary">Create New Patient</a>
                @endcan
            </div>
            <div class="table-responsive">
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module', 'responsive' => true]) }}
@endpush
