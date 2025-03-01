@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Add Previous Medications</h3>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('previous_medications.store', [$patient_id, $appointment_id]) }}">
        @csrf
        <table class="table table-bordered" id="medications-table">
            <thead class="thead-light">
                <tr>
                    <th>Medicine Name</th>
                    <th>Chemical</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" name="medications[0][medicine_name]" class="form-control" required>
                    </td>
                    <td>
                        <select name="medications[0][chemical_id]" class="form-control chemical-select" required>
                            <option></option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary" id="add-row">Add More</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 (and Bootstrap JS if needed) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

<script>
    $(document).ready(function() {
        // Keep track of the row index for new rows
        let rowIdx = 1;

        function initSelect2(element) {
            $(element).select2({
                ajax: {
                    url: '{{ route('chemicals.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        return { results: data };
                    },
                    cache: true
                },
                tags: true, // Allow users to add a new chemical if not found
                placeholder: 'Search or enter chemical code',
                minimumInputLength: 1,
                width: '100%'
            });
        }

        // Initialize select2 on the initial element
        initSelect2('.chemical-select');

        // Add more rows
        $('#add-row').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="medications[${rowIdx}][medicine_name]" class="form-control" required>
                    </td>
                    <td>
                        <select name="medications[${rowIdx}][chemical_id]" class="form-control chemical-select" required>
                            <option></option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </td>
                </tr>`;
            $('#medications-table tbody').append(newRow);
            // Initialize select2 on the new select element
            initSelect2($('#medications-table tbody tr:last .chemical-select'));
            rowIdx++;
        });

        // Remove row (ensuring at least one row remains)
        $(document).on('click', '.remove-row', function() {
            if ($('#medications-table tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });
    });
</script>
@endsection
