@extends('layouts.user_type.auth')

@section('content')
    <style>

    </style>

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">

                <span>Items</span>


            </div>
            <div class="row">
                <div class="col-8">
                </div>
                <div class="col-2">
                    <a href="{{ route('items.export-excel') }}" class="btn btn-success">Export to Excel</a>
                    <!-- Export Button -->
                </div>
                <div class="col-2">

                    <a href="{{ route('items.create') }}" class="btn btn-primary">Create Items</a>
                </div>
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
