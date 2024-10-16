@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h2 class="my-4 text-center">Doctor Appointment Calendar</h2>
        <div id="calendar" style="height: 800px;"></div>
    </div>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui.calendar/latest/tui-calendar.min.css">
    <script src="https://uicdn.toast.com/tui.calendar/latest/tui-calendar.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ensure the Calendar object is properly instantiated
            if (typeof tui !== 'undefined' && typeof tui.Calendar === 'function') {
                var calendar = new tui.Calendar('#calendar', {
                    defaultView: 'week', // Set default view
                    taskView: false,
                    scheduleView: true,
                    useCreationPopup: false,
                    useDetailPopup: true,
                    isReadOnly: true,
                    template: {
                        time: function(schedule) {
                            return '<strong>' + schedule.title + '</strong><br>' + schedule.location;
                        }
                    }
                });

                // Sample doctor appointments data
                calendar.createSchedules([{
                        id: '1',
                        calendarId: 'doctor',
                        title: 'John Doe - Consultation',
                        category: 'time',
                        start: '2024-10-15T09:00:00',
                        end: '2024-10-15T09:30:00',
                        location: 'General Check-up'
                    },
                    {
                        id: '2',
                        calendarId: 'doctor',
                        title: 'Jane Smith - Orthopedics',
                        category: 'time',
                        start: '2024-10-15T10:00:00',
                        end: '2024-10-15T11:00:00',
                        location: 'Knee pain check-up'
                    }
                ]);
            } else {
                console.error('TUI Calendar library not loaded properly.');
            }
        });
    </script>
@endsection
