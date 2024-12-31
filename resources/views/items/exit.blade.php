@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Item</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('items.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="item_code">Item Code</label>
                                <input type="text" name="item_code" id="item_code" class="form-control"
                                    value="{{ old('item_code', $item->item_code) }}" required>
                                @error('item_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $item->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="item_type">Item Type</label>
                                <select name="item_type" id="item_type" class="form-control" required>
                                    @foreach (\App\Models\Item::getItemTypes() as $key => $type)
                                        <option value="{{ $key }}" {{ $item->item_type == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="uom_type">UOM Type</label>
                                <select name="uom_type" id="uom_type" class="form-control" required>
                                    @foreach ($uomTypes as $uom)
                                        <option value="{{ $uom->id }}"
                                            {{ $item->uom_type == $uom->id ? 'selected' : '' }}>
                                            {{ $uom->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('uom_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <select name="brand" id="brand" class="form-control" required>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ $item->brand == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="source_company">Source Company</label>
                                <select name="source_company" id="source_company" class="form-control" required>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $item->source_company == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source_company')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="alert_quantity">Alert Quantity</label>
                                <input type="text" name="alert_quantity" id="alert_quantity" class="form-control"
                                    value="{{ old('alert_quantity', $item->alert_quantity) }}" required>
                                @error('alert_quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="ideal_quantity">Ideal Quantity</label>
                                <input type="text" name="ideal_quantity" id="ideal_quantity" class="form-control"
                                    value="{{ old('ideal_quantity', $item->ideal_quantity) }}" required>
                                @error('ideal_quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reorder_quantity">Reorder Quantity</label>
                                <input type="text" name="reorder_quantity" id="reorder_quantity" class="form-control"
                                    value="{{ old('reorder_quantity', $item->reorder_quantity) }}" required>
                                @error('reorder_quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
