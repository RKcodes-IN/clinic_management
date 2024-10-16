@extends('layouts.user_type.auth')

@section('content')
    <style>
        /* Same styles as before */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-right: 20px;
            border: 4px solid #007bff;
            transition: transform 0.3s;
        }

        .profile-header img:hover {
            transform: scale(1.05);
        }

        .badge-status {
            padding: 0.5em 1em;
            font-size: 0.85em;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
        }

        .profile-info-card {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .profile-info-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .profile-section-title {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.25rem;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .profile-info-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .profile-info-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f1f1f1;
            display: flex;
            justify-content: space-between;
            color: #343a40;
        }

        .profile-info-list li:last-child {
            border-bottom: none;
        }

        .table th,
        .table td {
            vertical-align: middle;
            font-size: 0.95rem;
        }

        /* Accordion Customization */
        .accordion-button {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .accordion-button:not(.collapsed) {
            color: white;
            background-color: #0056b3;
        }

        /* Arrow for accordion */
        .accordion-button::after {
            content: "\25BC";
            /* Arrow symbol */
            font-size: 1.2rem;
            position: absolute;
            right: 20px;
            transition: transform 0.3s ease;
        }

        .accordion-button.collapsed::after {
            transform: rotate(-90deg);
        }

        .accordion-item {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 10px;
        }

        .accordion-body {
            background-color: #f8f9fa;
        }

        .accordion-button:hover {
            background-color: #0062cc;
        }
    </style>

    <div class="container card p-4 shadow-lg">
        <!-- Profile Header Section -->
        <div class="profile-header">
            <img src="{{ $paitent->image ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png' }}"
                alt="Profile Picture">
            <div>
                <h3 class="mb-0">{{ $paitent->name ?? 'Unknown' }}</h3>
                <p class="text-muted">Patient ID: {{ $paitent->id }}</p>
            </div>
        </div>

        <!-- Patient Details -->
        <div class="profile-info-card">
            <h4 class="profile-section-title">Patient Information</h4>
            <ul class="profile-info-list">
                <li><strong>Date of Birth:</strong> {{ $paitent->date_of_birth ?? 'Not available' }}</li>
                <li><strong>Gender:</strong> {{ $paitent->gender ?? 'Not specified' }}</li>
                <li><strong>Contact:</strong> {{ $paitent->phone_number ?? 'N/A' }}</li>
                <li><strong>Address:</strong> {{ $paitent->address ?? 'No address provided' }}</li>
            </ul>
        </div>
        <div class="profile-info-card shadow-sm">
            <h4 class="profile-section-title">Health & Appointment Details</h4>
            <ul class="profile-info-list">
                <li>
                    <strong>Weight:</strong>
                    <span>{{ $healthEvaluation->weight }}</span>
                </li>
                <li>
                    <strong>Height:</strong>
                    <span>{{ $healthEvaluation->height }}</span>
                </li>
                <li>
                    <strong>Occupation:</strong>
                    <span>{{ $healthEvaluation->occupation }}</span>
                </li>
                <li>
                    <strong>Email:</strong>
                    <span>{{ $healthEvaluation->email }}</span>
                </li>
                <li>
                    <strong>Working Hours:</strong>
                    <span>{{ $healthEvaluation->working_hours }}</span>
                </li>
                <li>
                    <strong>Night Shift:</strong>
                    <span>{{ $healthEvaluation->night_shift }}</span>
                </li>
                <li>
                    <strong>Climatic Condition:</strong>
                    <span>{{ $healthEvaluation->climatic_condition }}</span>
                </li>
                <li>
                    <strong>Allergic to Drugs:</strong>
                    <span>{{ $healthEvaluation->allergic_to_drugs }}</span>
                </li>
                <li>
                    <strong>Allergic Drug Names:</strong>
                    <span>{{ $healthEvaluation->allergic_drug_names }}</span>
                </li>
                <li>
                    <strong>Food Allergies:</strong>
                    <span>{{ $healthEvaluation->food_allergies }}</span>
                </li>
                <li>
                    <strong>Lactose Tolerance:</strong>
                    <span>{{ $healthEvaluation->lactose_tolerance }}</span>
                </li>
                <li>
                    <strong>LMP:</strong>
                    <span>{{ $healthEvaluation->lmp }}</span>
                </li>
            </ul>
        </div>
        <!-- Past Histories Section -->
        <div class="profile-info-card">
            <h4 class="profile-section-title">Past Histories</h4>

            <div class="accordion" id="pastHistoryAccordion">
                @foreach ($pastHistories as $date => $histories)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $date }}">
                            <button class="accordion-button @if ($loop->first) @else collapsed @endif"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $date }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $date }}">
                                {{ $date }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $date }}"
                            class="accordion-collapse collapse @if ($loop->first) show @endif"
                            aria-labelledby="heading-{{ $date }}" data-bs-parent="#pastHistoryAccordion">
                            <div class="accordion-body">
                                <!-- Past Histories Table -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Condition</th>
                                            <th scope="col">Yes Or No</th>
                                            <th scope="col">No Of Years</th>
                                            <th scope="col">Trade Name</th>
                                            <th scope="col">Chemical</th>
                                            <th scope="col">Dose Freq</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $history)
                                            <tr>
                                                <td>{{ $history->pastHistory->name }}</td>
                                                <td>{{ $history->yes_no }}</td>
                                                <td>{{ $history->no_of_years }}</td>
                                                <td>{{ $history->trade_name }}</td>
                                                <td>{{ $history->chemical }}</td>
                                                <td>{{ $history->dose_freq }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Surgical Histories Section -->
        <div class="profile-info-card">
            <h4 class="profile-section-title">Surgical Histories</h4>

            <div class="accordion" id="surgicalHistoryAccordion">
                @foreach ($surgicalHistories as $date => $histories)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-surgical-{{ $date }}">
                            <button class="accordion-button @if ($loop->first) @else collapsed @endif"
                                type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-surgical-{{ $date }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="collapse-surgical-{{ $date }}">
                                {{ $date }}
                            </button>
                        </h2>
                        <div id="collapse-surgical-{{ $date }}"
                            class="accordion-collapse collapse @if ($loop->first) show @endif"
                            aria-labelledby="heading-surgical-{{ $date }}"
                            data-bs-parent="#surgicalHistoryAccordion">
                            <div class="accordion-body">
                                <!-- Surgical Histories Table -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Surgery Name</th>
                                            <th scope="col">Yes Or NO</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $history)
                                            <tr>
                                                <td>{{ $history->name }}</td>
                                                <td>{{ $history->yes_no }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="profile-info-card">
            <h4 class="profile-section-title">Addications</h4>

            <div class="accordion" id="surgicalHistoryAccordion">
                @foreach ($addications as $date => $histories)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-surgical-{{ $date }}">
                            <button class="accordion-button @if ($loop->first) @else collapsed @endif"
                                type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-surgical-{{ $date }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="collapse-surgical-{{ $date }}">
                                {{ $date }}
                            </button>
                        </h2>
                        <div id="collapse-surgical-{{ $date }}"
                            class="accordion-collapse collapse @if ($loop->first) show @endif"
                            aria-labelledby="heading-surgical-{{ $date }}"
                            data-bs-parent="#surgicalHistoryAccordion">
                            <div class="accordion-body">
                                <!-- Surgical Histories Table -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Yes Or NO</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $history)
                                            <tr>
                                                <td>{{ $history->name }}</td>
                                                <td>{{ $history->yes_no }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
