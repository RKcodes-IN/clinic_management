@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Stock Alert Notification Setting</h1>
        <form method="POST" action="{{ route('stock_alert_settings.update', $stockAlertSetting) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="frequency">Frequency</label>
                <select name="frequency" id="frequency" class="form-control" required>
                    <option value="daily" {{ $stockAlertSetting->frequency == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $stockAlertSetting->frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $stockAlertSetting->frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="time_of_day">Time of Day (HH:MM)</label>
                <input type="time" name="time_of_day" value="{{ $stockAlertSetting->time_of_day }}" class="form-control" required>
            </div>
            <div class="form-group" id="day_of_week_field" style="display: {{ $stockAlertSetting->frequency == 'weekly' ? 'block' : 'none' }};">
                <label for="day_of_week">Day of Week</label>
                <select name="day_of_week" class="form-control">
                    <option value="1" {{ $stockAlertSetting->day_of_week == 1 ? 'selected' : '' }}>Monday</option>
                    <option value="2" {{ $stockAlertSetting->day_of_week == 2 ? 'selected' : '' }}>Tuesday</option>
                    <option value="3" {{ $stockAlertSetting->day_of_week == 3 ? 'selected' : '' }}>Wednesday</option>
                    <option value="4" {{ $stockAlertSetting->day_of_week == 4 ? 'selected' : '' }}>Thursday</option>
                    <option value="5" {{ $stockAlertSetting->day_of_week == 5 ? 'selected' : '' }}>Friday</option>
                    <option value="6" {{ $stockAlertSetting->day_of_week == 6 ? 'selected' : '' }}>Saturday</option>
                    <option value="7" {{ $stockAlertSetting->day_of_week == 7 ? 'selected' : '' }}>Sunday</option>
                </select>
            </div>
            <div class="form-group" id="day_of_month_field" style="display: {{ $stockAlertSetting->frequency == 'monthly' ? 'block' : 'none' }};">
                <label for="day_of_month">Day of Month</label>
                <input type="number" name="day_of_month" value="{{ $stockAlertSetting->day_of_month }}" min="1" max="31" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>

    <script>
        document.getElementById('frequency').addEventListener('change', function() {
            const frequency = this.value;
            document.getElementById('day_of_week_field').style.display = frequency === 'weekly' ? 'block' : 'none';
            document.getElementById('day_of_month_field').style.display = frequency === 'monthly' ? 'block' : 'none';
        });
    </script>
@endsection
