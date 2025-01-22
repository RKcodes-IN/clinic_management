@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Item</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('items.store') }}" method="POST">
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
                                <select name="item_type" id="item_type" class="form-control" required>
                                    @php
                                        // Get the 'type' from the URL
                                        $urlType = request('type');
                                        // Determine the selected type based on the URL or default logic
                                        if ($urlType == 'lab') {
                                            $selectedType = \App\Models\Item::TYPE_LAB; // Select 'lab' if passed
                                        } elseif ($urlType == 'miss') {
                                            $selectedType = \App\Models\Item::MISCELLANEOUS; // Select 'MISCELLANEOUS' if passed
                                        } else {
                                            $selectedType = \App\Models\Item::TYPE_PHARMACY; // Default to 'Pharmacy' if no valid type is passed
                                        }
                                    @endphp
                                    @foreach (\App\Models\Item::getItemTypes() as $key => $type)
                                        <option value="{{ $key }}" {{ $selectedType == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            @php
                                // Get the 'type' from the URL
                                $urlType = request('type');
                                // Determine the selected type based on the URL or default logic
                            @endphp
                            @if ($urlType == 'lab' || $urlType == 'miss')
                                <div class="form-group">
                                    <label for="item_code">Re-Order Quantity</label>
                                    <input type="text" name="reorder_quantity" id="reorder_quantity" class="form-control"
                                        required>
                                    @error('item_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="uom_type">UOM Type</label>
                                    <select name="uom_type" id="uom_type" class="form-control" required>
                                        @foreach ($uomTypes as $uom)
                                            <option value="{{ $uom->id }}">{{ $uom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('uom_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <select name="brand" id="brand" class="form-control" required>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="source_company">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('source_company')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="source_company">Source Company</label>
                                <select name="source_company" id="source_company" class="form-control" required>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @error('source_company')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="item_code">Alert Quantity</label>
                                <input type="text" name="alert_quantity" id="alert_quantity" class="form-control"
                                    required>
                                @error('item_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="item_code">Ideal Quantity</label>
                                <input type="text" name="ideal_quantity" id="ideal_quantity" class="form-control"
                                    required>
                                @error('item_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>



                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
