@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Invoice</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('invoice.store') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pharmacy-items-table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all-items"></th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Batch Number</th>
                                            <th>Expiry Date</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" name="items[0][selected]" class="item-checkbox"></td>
                                            <td>
                                                <select name="items[0][item_id]" class="form-control" required>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}">{{ $stock->item->name??"" }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="items[0][quantity]" class="form-control"
                                                    required></td>
                                            <td><input type="text" name="items[0][batch_number]" class="form-control"
                                                    required></td>
                                            <td><input type="date" name="items[0][expiry_date]" class="form-control"
                                                    required></td>
                                            <td><input type="number" step="0.01" name="items[0][price]"
                                                    class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="items[0][total]"
                                                    class="form-control" readonly></td>
                                            <td><input type="text" name="items[0][description]" class="form-control">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="add-row" class="btn btn-secondary">Add More</button>
                            <button type="submit" class="btn btn-primary mt-3">Create Invoice</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;

        document.getElementById('add-row').addEventListener('click', function() {
            const tableBody = document.querySelector('#pharmacy-items-table tbody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="checkbox" name="items[${rowIndex}][selected]" class="item-checkbox"></td>
                <td>
                    <select name="items[${rowIndex}][item_id]" class="form-control" required>
                        @foreach ($stocks as $stock)
                            <option value="{{ $stock->id }}">{{ $stock->stock->name??"" }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control" required></td>
                <td><input type="text" name="items[${rowIndex}][batch_number]" class="form-control" required></td>
                <td><input type="date" name="items[${rowIndex}][expiry_date]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="items[${rowIndex}][price]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="items[${rowIndex}][total]" class="form-control" readonly></td>
                <td><input type="text" name="items[${rowIndex}][description]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            `;

            tableBody.appendChild(newRow);
            rowIndex++;
        });

        document.getElementById('pharmacy-items-table').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        document.getElementById('select-all-items').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
@endsection
