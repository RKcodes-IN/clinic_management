<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            {{-- <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">{{ str_replace('-', ' ', Request::path()) }}</li>
            </ol> --}}
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ Auth::user()->roles->pluck('name')[0] ?? '' }}
                Dashboard</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">


            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="{{ url('/logout') }}" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Sign Out</span>
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                {{-- <li class="nav-item px-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                </a>
            </li> --}}
                <li class="nav-item dropdown px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        <!-- Unread notification count -->
                        <span id="notificationCount" class="badge bg-danger"></span>
                    </a>
                    <ul id="notificationDropdown" class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4"
                        aria-labelledby="dropdownMenuButton">
                        <!-- Notifications will be injected here -->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        // Function to fetch notifications
        function fetchNotifications() {
            $.ajax({
                url: '/notifications',
                type: 'GET',
                dataType: 'json',
                success: function(notifications) {
                    // Clear current notifications
                    $('#notificationDropdown').empty();

                    // Check if notifications exist
                    if (notifications.length > 0) {
                        // Update notification count badge
                        $('#notificationCount').text(notifications.filter(function(n) {
                            return !n.read_at;
                        }).length);

                        // Loop through notifications and add them to the dropdown
                        $.each(notifications, function(index, notification) {
                            var notificationItem = `
    <li class="mb-2">
        <a class="dropdown-item border-radius-md" href="/notification/details/${notification.id}">
            <div class="d-flex py-1">
                <div class="my-auto">
                    <!-- Notification icon -->
                    <i class="fa fa-info-circle text-primary me-3"></i>
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">${notification.title}</h6>
                    <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i> ${moment(notification.created_at).fromNow()}
                    </p>
                </div>
            </div>
        </a>
    </li>`;


                            $('#notificationDropdown').append(notificationItem);
                        });
                    } else {
                        $('#notificationDropdown').append(
                            '<li class="mb-2"><span class="dropdown-item text-center text-muted">No notifications</span></li>'
                        );
                        $('#notificationCount').text('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching notifications:", error);
                }
            });
        }

        // Call the function on page load
        fetchNotifications();

        // Optionally, refresh notifications every 60 seconds
        setInterval(fetchNotifications, 60000);
    });
</script>

<!-- Moment.js for time formatting -->
