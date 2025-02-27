@extends('layouts.user_type.auth')

@section('content')
    <style>
        .dose-details.mt-3 {
            overflow: hidden;
            overflow-x: scroll;
        }
    </style>
    <div class="container">
        <h1>Create Pharmacy Prescription</h1>
        <form action="#" method="POST">
            @csrf
            <!-- Container for all item entries -->
            <div id="items-container">
                <!-- Single item entry (the first one) -->
                <div class="item-entry border p-3 mb-3" data-index="0">
                    <div class="row">
                        <!-- Item Dropdown (searchable) -->
                        <div class="col-md-3">
                            <label>Item</label>
                            <select class="form-control item-select" name="items[0][item_id]">
                                <option value="">Select Item</option>
                                @foreach ($pharmacyItems as $item)
                                    <option value="{{ $item->id }}" data-category="{{ $item->category_id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Auto-filled Category -->
                        <div class="col-md-3">
                            <label>Category</label>
                            <input type="text" class="form-control category" name="items[0][category]" readonly>
                        </div>
                        <!-- Common Instruction -->
                        <div class="col-md-3">
                            <label>Common Instruction</label>
                            <input type="text" class="form-control" name="items[0][common_instruction]">
                        </div>
                        <!-- Starting Instruction -->
                        <div class="col-md-3">
                            <label>Starting Instruction</label>
                            <input type="text" class="form-control" name="items[0][starting_instruction]">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- Special Instruction -->
                        <div class="col-md-3">
                            <label>Special Instruction</label>
                            <input type="text" class="form-control" name="items[0][special_instruction]">
                        </div>
                        <!-- No. of Doses -->
                        <div class="col-md-3">
                            <label>No. of Doses</label>
                            <input type="number" class="form-control dose-count" name="items[0][dose_count]" min="0"
                                value="0">
                        </div>
                        <!-- Duration Unit as checkboxes in the main item -->
                        <div class="col-md-3">
                            <label>Duration Unit</label>
                            <input type="number" class="form-control duration_unit" name="items[0][duration]" min="0"
                                value="0">
                            <div>
                                <label><input type="checkbox" name="items[0][duration_unit][]" value="D"> Days</label>
                                <label><input type="checkbox" name="items[0][duration_unit][]" value="W"> Weeks</label>
                                <label><input type="checkbox" name="items[0][duration_unit][]" value="M">
                                    Months</label>
                            </div>
                        </div>
                        <!-- Remove Item Button -->
                        <div class="col-md-3 align-self-end">
                            <button type="button" class="btn btn-danger remove-item">Remove Item</button>
                        </div>
                    </div>
                    <!-- Container where the dose details table will be generated -->
                    <div class="dose-details mt-3"></div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add_item">Add Item</button>
            <br /><br />
            <button type="submit" class="btn btn-success">Submit Prescription</button>
        </form>
    </div>

    <!-- Hidden template for a single dose row -->
    <script type="text/template" id="dose-row-template">
        <tr>
            <td>{dose_number}</td>
            <!-- Dose Quantity Column: two radio buttons with inline text fields -->
            <td>
                <div class="form-inline">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="items[{item_index}][doses][{dose_index}][dose_quantity_option]" value="single">
                        <label class="form-check-label">Single</label>
                    </div>
                    <div class="form-check form-check-inline ml-2">
                        <input class="form-check-input" type="radio" name="items[{item_index}][doses][{dose_index}][dose_quantity_option]" value="multiple">
                        <label class="form-check-label">Multiple</label>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm ml-1" name="items[{item_index}][doses][{dose_index}][dose_quantity_single]" placeholder="Qty">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm ml-1" name="items[{item_index}][doses][{dose_index}][dose_quantity_multiple]" placeholder="Qty">
                        </div>
                    </div>
                </div>
            </td>
            <!-- Dose Unit Column -->
            <td>
                <input type="text" class="form-control form-control-sm" name="items[{item_index}][doses][{dose_index}][dose_unit]">
            </td>
            <!-- Reference Column: inline checkboxes for Food, Sleep, and Daytime -->
            <td>
                <div class="form-inline">
                    <div class="form-check ">
                        <input class="form-check-input" type="checkbox" name="items[{item_index}][doses][{dose_index}][reference][]" value="food">
                        <label class="form-check-label">Food</label>
                    </div>
                    <div class="form-check ">
                        <input class="form-check-input" type="checkbox" name="items[{item_index}][doses][{dose_index}][reference][]" value="sleep">
                        <label class="form-check-label">Sleep</label>
                    </div>
                    <div class="form-check ">
                        <input class="form-check-input" type="checkbox" name="items[{item_index}][doses][{dose_index}][reference][]" value="daytime">
                        <label class="form-check-label">Daytime</label>
                    </div>
                </div>
            </td>
            <!-- Direct Preparation: two radio buttons and two text fields -->
            <td>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="items[{item_index}][doses][{dose_index}][direct_preparation]" value="single">
                    <label class="form-check-label">Single</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="items[{item_index}][doses][{dose_index}][direct_preparation]" value="multiple">
                    <label class="form-check-label">Multiple</label>
                </div>
                <div class="row mt-1">
                    <div class="col-6">
                        <input type="text" class="form-control form-control-sm" name="items[{item_index}][doses][{dose_index}][direct_preparation_text1]" placeholder="Text Field 1">
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control form-control-sm" name="items[{item_index}][doses][{dose_index}][direct_preparation_text2]" placeholder="Text Field 2">
                    </div>
                </div>
            </td>
            <!-- Mixture: radio buttons for Yes and No -->
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="items[{item_index}][doses][{dose_index}][mixture]" value="yes">
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="items[{item_index}][doses][{dose_index}][mixture]" value="no">
                    <label class="form-check-label">No</label>
                </div>
            </td>
            <!-- MX With -->
            <td>
                <input type="text" class="form-control form-control-sm" name="items[{item_index}][doses][{dose_index}][mx_with]">
            </td>
            <!-- Mix Proportion -->
            <td>
                <input type="text" class="form-control form-control-sm" name="items[{item_index}][doses][{dose_index}][mix_proportion]">
            </td>
            <!-- Final Proportion -->
            <td>
                <input type="text" class="form-control form-control-sm" name="items[{item_index}][doses][{dose_index}][final_proportion]">
            </td>
        </tr>
</script>

    <!-- Include jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 on all item dropdowns
            $('.item-select').select2({
                placeholder: 'Select an item',
                width: '100%'
            });

            // Auto-fill the category field when an item is selected
            $(document).on('change', '.item-select', function() {
                var category = $(this).find(':selected').data('category');
                $(this).closest('.item-entry').find('.category').val(category);
            });

            // When the "No. of Doses" value changes, build the dose details table
            $(document).on('change', '.dose-count', function() {
                var doseCount = parseInt($(this).val());
                var itemEntry = $(this).closest('.item-entry');
                var itemIndex = itemEntry.data('index');
                var doseDetailsDiv = itemEntry.find('.dose-details');
                doseDetailsDiv.empty(); // Clear any previous table

                if (doseCount > 0) {
                    // Build the dose table with compact styling
                    var table = $(
                        '<table class="table table-bordered table-responsive table-sm dose-table"></table>'
                    );
                    var thead = $(`
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Dose<br>Quantity</th>
                        <th>Dose<br>Unit</th>
                        <th>Reference</th>
                        <th>Direct<br>Preparation</th>
                        <th>Mixture</th>
                        <th>MiX<br>With</th>
                        <th>Mix<br>Proportion</th>
                        <th>Final<br>Proportion</th>
                    </tr>
                </thead>
            `);
                    var tbody = $('<tbody></tbody>');
                    // Generate dose rows based on the number entered
                    for (var i = 0; i < doseCount; i++) {
                        var rowTemplate = $('#dose-row-template').html();
                        rowTemplate = rowTemplate.replace(/{dose_number}/g, i + 1)
                            .replace(/{item_index}/g, itemIndex)
                            .replace(/{dose_index}/g, i);
                        tbody.append(rowTemplate);
                    }
                    table.append(thead).append(tbody);
                    doseDetailsDiv.append(table);
                }
            });

            // Add a new item entry
            var itemIndex = 1;
            $('#add_item').click(function() {
                var newItem = $('.item-entry:first').clone();
                newItem.attr('data-index', itemIndex);
                // Reset all input fields and update name attributes for the new item
                newItem.find('select.item-select')
                    .val('')
                    .trigger('change')
                    .attr('name', 'items[' + itemIndex + '][item_id]');
                newItem.find('.category')
                    .val('')
                    .attr('name', 'items[' + itemIndex + '][category]');
                newItem.find('input[name*="[common_instruction]"]')
                    .val('')
                    .attr('name', 'items[' + itemIndex + '][common_instruction]');
                newItem.find('input[name*="[starting_instruction]"]')
                    .val('')
                    .attr('name', 'items[' + itemIndex + '][starting_instruction]');
                newItem.find('input[name*="[special_instruction]"]')
                    .val('')
                    .attr('name', 'items[' + itemIndex + '][special_instruction]');
                newItem.find('.dose-count')
                    .val(0)
                    .attr('name', 'items[' + itemIndex + '][dose_count]');
                // Update the Duration Unit checkboxes in the main item row

                newItem.find('.dose-details').empty();
                itemIndex++;
                $('#items-container').append(newItem);
                // Reinitialize Select2 on the new dropdown
                newItem.find('.item-select').select2({
                    placeholder: 'Select an item',
                    width: '100%'
                });
            });

            // Remove an item entry (ensure at least one remains)
            $(document).on('click', '.remove-item', function() {
                if ($('.item-entry').length > 1) {
                    $(this).closest('.item-entry').remove();
                } else {
                    alert('At least one item is required.');
                }
            });
        });
    </script>
@endsection
