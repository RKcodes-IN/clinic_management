@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Items</span>
                <a href="{{ route('items.create') }}" class="btn btn-primary">Create Items</a>
                <a href="{{ route('items.export-excel') }}" class="btn btn-success">Export to Excel</a> <!-- Export Button -->

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
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

@endpush
