@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <!-- Compact Patient Info -->
        <div class="card mb-3">
            <div class="card-body p-3">
                @if ($patientDetails)
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="patient-info-main">
                            <h6 class="mb-1">{{ $patientDetails->name }}</h6>
                            <div class="d-flex gap-3 text-sm">
                                <span><i class="fas fa-user me-1"></i>{{ ucfirst($patientDetails->gender) }},
                                    {{ $patientDetails->age }}</span>
                                <span><i class="fas fa-phone me-1"></i>{{ $patientDetails->phone_number }}</span>
                                <span class="text-truncate" style="max-width: 300px;">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ implode(
                                        ', ',
                                        array_filter([$patientDetails->address, $patientDetails->place, $patientDetails->city, $patientDetails->pincode]),
                                    ) }}
                                </span>
                            </div>
                        </div>
                        @if ($appointmentId)
                            <div class="text-muted text-sm mt-2 mt-sm-0">
                                <div>Appointment ID: {{ $appointmentId }}</div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-danger mb-0">Patient details not found!</div>
                @endif
            </div>
        </div>

        <!-- Therapy Form -->
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="mb-0">Therapy</h5>
                <p class="text-sm mb-0">Select therapies and specify sub categories</p>
            </div>

            <div class="card-body pt-2">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('therapy.store') }}">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patientId }}">
                    <input type="hidden" name="appointment_id" value="{{ $appointmentId }}">

                    <div class="table-responsive">
                        <table class="table align-items-center" id="therapy-table">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th style="width: 50%">Therapy Name</th>
                                    <th>Sub Category</th>
                                    <th style="width: 80px" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="therapy[0][item]" class="form-select select2" required>
                                            <option value="" selected>Select Therapy</option>
                                            @foreach ($therapyItems as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="therapy[0][sub_category]" class="form-control"
                                            placeholder="Sub category...">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-icon btn-success btn-sm add-row">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-paper-plane me-2"></i> Submit Therapies
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                placeholder: "Select Therapy",
                width: '100%',
                minimumResultsForSearch: 6
            });

            $(document).on('click', '.add-row', function() {
                const table = $(this).closest('table');
                const rowCount = table.find('tbody tr').length;
                const items = @json($therapyItems);

                let itemOptions = '<option value="" selected>Select Therapy</option>';
                items.forEach(item => {
                    itemOptions += `<option value="${item.id}">${item.name}</option>`;
                });

                const newRow = `
        <tr>
            <td>
                <select name="therapy[${rowCount}][item]" class="form-select select2" required>
                    ${itemOptions}
                </select>
            </td>
            <td>
                <input type="text" name="therapy[${rowCount}][sub_category]" class="form-control"
                       placeholder="Sub category...">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-icon btn-danger btn-sm remove-row">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;

                table.find('tbody').append(newRow);
                table.find('tbody tr:last .select2').select2();
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
