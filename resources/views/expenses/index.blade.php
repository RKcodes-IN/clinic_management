@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Expense's Lists</span>
                {{-- @can('create expense') --}}
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary">Create New Expense</a>
                {{-- @endcan --}}
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
