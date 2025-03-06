@extends('layouts.user_type.auth')

@section('content')
    <div class="container my-4">
        <h1>Stock Alerts</h1>

        <div class="mt-4">
            @php
                $rawMessage = $notification->message ?? '';
                $jsonData = json_decode($rawMessage, true);
                $isValidJson = json_last_error() === JSON_ERROR_NONE;

                if (!$isValidJson) {
                    preg_match('/(\{.*\})|(\[.*\])/s', $rawMessage, $matches);
                    if (!empty($matches)) {
                        $jsonData = json_decode($matches[0], true);
                        $isValidJson = json_last_error() === JSON_ERROR_NONE;
                    }
                }
            @endphp

            @if ($isValidJson && isset($jsonData['stock_alerts']))
                @foreach ($jsonData['stock_alerts'] as $companyName => $companyData)
                    <div class="company-section mb-5">
                        <h3 class="mb-3">{{ $companyName }}</h3>

                        @if (!empty($companyData) && is_array($companyData))
                            @php
                                $headers = array_keys($companyData[0] ?? []);
                            @endphp

                            <table id="table_{{ Str::slug($companyName) }}"
                                   class="table table-bordered table-striped stock-table">
                                <thead class="thead-dark">
                                    <tr>
                                        @foreach ($headers as $header)
                                            <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companyData as $item)
                                        <tr @if(($item['available_stock'] ?? 0) < 0) class="" @endif>
                                            @foreach ($headers as $header)
                                                <td>
                                                    @if(is_array($item[$header] ?? null))
                                                        {{ json_encode($item[$header]) }}
                                                    @else
                                                        {{ $item[$header] ?? 'N/A' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning">
                                No stock alerts available for this company
                            </div>
                        @endif
                    </div>
                @endforeach
            @elseif($isValidJson)
                <div class="alert alert-danger">
                    Invalid stock alert format - missing 'stock_alerts' key
                </div>
            @else
                <div class="alert alert-info">
                    {{ $rawMessage }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('.stock-table').each(function() {
                $(this).DataTable({
                    "pageLength": 10,
                    "order": [],
                    "columnDefs": [
                        { "targets": -1, "searchable": false } // Disable search on last column
                    ],
                    "language": {
                        "search": "Filter:"
                    }
                });
            });
        });
    </script>

    <style>
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
            border: 1px solid #dee2e6;
            padding: 0.25rem 0.5rem;
        }

        .stock-table th {
            background: #f8f9fa;
            white-space: nowrap;
        }

        .company-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
    </style>
@endsection
