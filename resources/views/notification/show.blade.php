@extends('layouts.user_type.auth')

@section('content')
<div class="container my-4">
    <h1>{{ $notification->title }}</h1>

    <div class="mt-4">
        @php
            $rawMessage = $notification->message;
            $jsonData = json_decode($rawMessage, true);
            $isValidJson = json_last_error() === JSON_ERROR_NONE;

            // Try to extract JSON from message if initial parse fails
            if (!$isValidJson) {
                preg_match('/(\{.*\})|(\[.*\])/s', $rawMessage, $matches);
                if (!empty($matches)) {
                    $jsonData = json_decode($matches[0], true);
                    $isValidJson = json_last_error() === JSON_ERROR_NONE;
                }
            }

            $dataArray = null;
            $headers = [];

            if ($isValidJson && is_array($jsonData)) {
                // Check if JSON is an associative array
                $isAssociative = (array_keys($jsonData)) !== range(0, count($jsonData) - 1);

                if ($isAssociative) {
                    // Look for nested array in values
                    foreach ($jsonData as $value) {
                        if (is_array($value)) {
                            $dataArray = $value;
                            break;
                        }
                    }
                    // Fallback to treating the entire array as data
                    if (!$dataArray) $dataArray = [$jsonData];
                } else {
                    // Handle list arrays
                    $dataArray = $jsonData;
                }

                // Determine headers
                if (!empty($dataArray)) {
                    $firstItem = reset($dataArray);
                    $headers = is_array($firstItem) ? array_keys($firstItem) : array_keys($dataArray);
                }
            }
        @endphp

        @if($isValidJson && !empty($headers))
            <table id="notificationTable" class="table table-bordered">
                <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if(is_array(reset($dataArray)))
                        @foreach($dataArray as $row)
                            <tr>
                                @foreach($headers as $key)
                                    <td>{{ isset($row[$key]) ? (is_array($row[$key]) ? json_encode($row[$key]) : $row[$key]) : '' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            @foreach($dataArray as $value)
                                <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                            @endforeach
                        </tr>
                    @endif
                </tbody>
            </table>
        @else
            <p>{{ $notification->message }}</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function(){
            $('#notificationTable').DataTable();
        });
    </script>
@endsection
