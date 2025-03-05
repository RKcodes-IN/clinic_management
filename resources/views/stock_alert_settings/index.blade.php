@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Stock Alert Notification Settings</h1>
        <a href="{{ route('stock_alert_settings.create') }}" class="btn btn-primary">Create New Setting</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Frequency</th>
                    <th>Time of Day</th>
                    <th>Day of Week</th>
                    <th>Day of Month</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $setting->id }}</td>
                        <td>{{ $setting->frequency }}</td>
                        <td>{{ $setting->time_of_day }}</td>
                        <td>{{ $setting->day_of_week ?? 'N/A' }}</td>
                        <td>{{ $setting->day_of_month ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('stock_alert_settings.show', $setting) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('stock_alert_settings.edit', $setting) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('stock_alert_settings.destroy', $setting) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
