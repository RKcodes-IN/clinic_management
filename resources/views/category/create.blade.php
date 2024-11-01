@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Stock</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('stock.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="item_code_name">Item Code Name</label>
                                <input type="text" name="item_code_name" id="item_code_name" class="form-control" value="{{ old('item_code_name') }}" required>
                                @error('item_code_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="invoice_number">Invoice Number</label>
                                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number') }}" required>
                                @error('invoice_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="purchase_invoice_date">Purchase Invoice Date</label>
                                <input type="date" name="purchase_invoice_date" id="purchase_invoice_date" class="form-control" value="{{ old('purchase_invoice_date') }}" required>
                                @error('purchase_invoice_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="source_name">Source Name</label>
                                <input type="number" name="source_name" id="source_name" class="form-control" value="{{ old('source_name') }}" required>
                                @error('source_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="number" name="brand" id="brand" class="form-control" value="{{ old('brand') }}" required>
                                @error('brand')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="number" name="category" id="category" class="form-control" value="{{ old('category') }}" required>
                                @error('category')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="batch">Batch</label>
                                <input type="text" name="batch" id="batch" class="form-control" value="{{ old('batch') }}" required>
                                @error('batch')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                                @error('expiry_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="hsn_code">HSN Code</label>
                                <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="{{ old('hsn_code') }}" required>
                                @error('hsn_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="uom_type">UOM Type</label>
                                <input type="number" name="uom_type" id="uom_type" class="form-control" value="{{ old('uom_type') }}" required>
                                @error('uom_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="mrp">MRP</label>
                                <input type="number" name="mrp" id="mrp" class="form-control" value="{{ old('mrp') }}" step="0.01" required>
                                @error('mrp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="discount_percentage">Discount Percentage</label>
                                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" value="{{ old('discount_percentage') }}" step="0.01" required>
                                @error('discount_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="discount_price">Discount Price</label>
                                <input type="number" name="discount_price" id="discount_price" class="form-control" value="{{ old('discount_price') }}" step="0.01" required>
                                @error('discount_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="additional_discount_percentage">Additional Discount Percentage</label>
                                <input type="number" name="additional_discount_percentage" id="additional_discount_percentage" class="form-control" value="{{ old('additional_discount_percentage') }}" step="0.01" required>
                                @error('additional_discount_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="additional_discount_price">Additional Discount Price</label>
                                <input type="number" name="additional_discount_price" id="additional_discount_price" class="form-control" value="{{ old('additional_discount_price') }}" step="0.01" required>
                                @error('additional_discount_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gst_type">GST Type</label>
                                <input type="number" name="gst_type" id="gst_type" class="form-control" value="{{ old('gst_type') }}" required>
                                @error('gst_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gst_amount">GST Amount</label>
                                <input type="number" name="gst_amount" id="gst_amount" class="form-control" value="{{ old('gst_amount') }}" step="0.01" required>
                                @error('gst_amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="cost_price">Cost Price</label>
                                <input type="number" name="cost_price" id="cost_price" class="form-control" value="{{ old('cost_price') }}" step="0.01" required>
                                @error('cost_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="courier_price_percentage">Courier Price Percentage</label>
                                <input type="number" name="courier_price_percentage" id="courier_price_percentage" class="form-control" value="{{ old('courier_price_percentage') }}" step="0.01" required>
                                @error('courier_price_percentage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="courier_charge_amount">Courier Charge Amount</label>
                                <input type="number" name="courier_charge_amount" id="courier_charge_amount" class="form-control" value="{{ old('courier_charge_amount') }}" step="0.01" required>
                                @error('courier_charge_amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="final_cost_price">Final Cost Price</label>
                                <input type="number" name="final_cost_price" id="final_cost_price" class="form-control" value="{{ old('final_cost_price') }}" step="0.01" required>
                                @error('final_cost_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sale_price">Sale Price</label>
                                <input type="number" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price') }}" step="0.01" required>
                                @error('sale_price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sale_discount">Sale Discount</label>
                                <input type="number" name="sale_discount" id="sale_discount" class="form-control" value="{{ old('sale_discount') }}" step="0.01" required>
                                @error('sale_discount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="profit_margin">Profit Margin</label>
                                <input type="number" name="profit_margin" id="profit_margin" class="form-control" value="{{ old('profit_margin') }}" step="0.01" required>
                                @error('profit_margin')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="purchase_quantity">Purchase Quantity</label>
                                <input type="number" name="purchase_quantity" id="purchase_quantity" class="form-control" value="{{ old('purchase_quantity') }}" step="0.01" required>
                                @error('purchase_quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="delete" {{ old('status') == 'delete' ? 'selected' : '' }}>Delete</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Create Stock</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
