@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-5">
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
                                    @foreach (\App\Models\Item::getItemTypes() as $key => $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
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
                                        <option value="{{ $uom->id }}">{{ $uom->name }}</option>
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
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @error('source_company')
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
