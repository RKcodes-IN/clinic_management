@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Create Stock Alert Notification Setting</h1>
        <form method="POST" action="{{ route('stock_alert_settings.store') }}">
            @csrf
            <div class="form-group">
                <label for="frequency">Frequency</label>
                <select name="frequency" id="frequency" class="form-control" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="time_of_day">Time of Day (HH:MM)</label>
                <input type="time" name="time_of_day" class="form-control" required>
            </div>
            <div class="form-group" id="day_of_week_field" style="display: none;">
                <label for="day_of_week">Day of Week</label>
                <select name="day_of_week" class="form-control">
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                    <option value="7">Sunday</option>
                </select>
            </div>
            <div class="form-group" id="day_of_month_field" style="display: none;">
                <label for="day_of_month">Day of Month</label>
                <input type="number" name="day_of_month" min="1" max="31" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save</button>
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
