@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            @can('create doctordetail')
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Doctor</span>
                <a href="{{ route('doctorDetail.create') }}" class="btn btn-primary">Create New Doctor</a>
            </div>
            @endcan
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
