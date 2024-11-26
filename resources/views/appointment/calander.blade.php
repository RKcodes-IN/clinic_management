@extends('layouts.user_type.auth')

@section('content')
@push('style')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.css' rel='stylesheet' />
<style>
    #calendar {
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px;
        height: 600px;
    }
</style>
@endpush
<div id="calendar"></div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: function(fetchInfo, successCallback, failureCallback) {
                // Fetch data from your server using axios
                axios.get('/api/doctor/appointments')
                    .then(function (response) {
                        successCallback(response.data);
                    })
                    .catch(function (error) {
                        console.error('Error fetching appointments:', error);
                        failureCallback(error);
                    });
            },
            eventClick: function(info) {
                // Display more info when an event is clicked
                alert(
                    'Appointment with: ' + info.event.title + '\n' +
                    'Email: ' + info.event.extendedProps.email + '\n' +
                    'Phone Number: ' + info.event.extendedProps.phone_number + '\n' +
                    'Message: ' + info.event.extendedProps.message
                );
            }
        });

        calendar.render();
    });
</script>
@endpush
