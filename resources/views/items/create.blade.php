@extends('layouts.user_type.auth')

@section('content')
    @php
        $urlType = $type ?? '';

    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Item</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('items.store') }}" method="POST" id="itemForm">
                            @csrf
                            <div class="form-group">
                                <label for="item_code">Item Code</label>
                                <input type="text" name="item_code" id="item_code" class="form-control" required>
                                @error('item_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="item_type">Item Type</label>
                                <select name="item_type" id="item_type" class="form-control" disabled>
                                    @php
                                        $selectedType =
                                            $urlType == 'lab'
                                                ? \App\Models\Item::TYPE_LAB
                                                : ($urlType == 'miss'
                                                    ? \App\Models\Item::MISCELLANEOUS
                                                    : ($urlType == 'therapy'
                                                        ? \App\Models\Item::TYPE_THERAPY
                                                        : \App\Models\Item::TYPE_PHARMACY));
                                    @endphp
                                    @foreach (\App\Models\Item::getItemTypes() as $key => $type)
                                        <option value="{{ $key }}" {{ $selectedType == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="item_type" value="{{ $selectedType }}">
                                @error('item_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div id="conditionalFields">
                                <div class="form-group">
                                    <label for="reorder_quantity">Re-Order Quantity</label>
                                    <input type="text" name="reorder_quantity" id="reorder_quantity"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <select name="brand" id="brand" class="form-control">
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="source_company">Source Company</label>
                                    <select name="source_company" id="source_company" class="form-control">
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="alert_quantity">Alert Quantity</label>
                                    <input type="text" name="alert_quantity" id="alert_quantity" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="ideal_quantity">Ideal Quantity</label>
                                    <input type="text" name="ideal_quantity" id="ideal_quantity" class="form-control">
                                </div>
                                <div class="form-group" id="uomGroup">
                                    <label for="uom_type">UOM Type</label>
                                    <select name="uom_type" id="uom_type" class="form-control">
                                        @foreach ($uomTypes as $uom)
                                            <option value="{{ $uom->id }}">{{ $uom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemType = "{{ $urlType }}"; // Use the URL type from the controller
            const conditionalFields = document.getElementById('conditionalFields');
            const uomGroup = document.getElementById('uomGroup');

            // Show or hide fields based on the item type
            if (itemType === "lab" || itemType === "miss" || itemType === "therapy") {
                conditionalFields.style.display = 'none';
            } else {
                conditionalFields.style.display = 'block';
            }
        });
    </script>
@endsection
