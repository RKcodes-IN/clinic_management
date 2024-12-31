@extends('layouts.app')
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet"> --}}

<!-- DataTables CSS -->
{{-- <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css" rel="stylesheet"> --}}
<link rel="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.bootstrap4.min.css" />
<!-- DataTables Buttons CSS -->
<link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap4.css" rel="stylesheet">
@stack('style') {{-- This will yield the scripts section --}}
<style>
    .navbar-vertical.navbar-expand-xs {
        background: #fff !important;
        margin: 0 !important;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .16) !important;
    }

    .ps__rail-y {
        display: none !important;
    }

    .navbar-vertical.navbar-expand-xs .navbar-collapse {
        overflow-y: scroll !important;
    }

    .main-content {
        overflow-y: scroll !important;
    }

    body {

        overflow: hidden !important;
    }
</style>

@section('auth')


    @if (\Request::is('static-sign-up'))
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')
    @elseif (\Request::is('static-sign-in'))
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')
    @else
        @if (\Request::is('rtl'))
            @include('layouts.navbars.auth.sidebar-rtl')
            <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg overflow-hidden">
                @include('layouts.navbars.auth.nav-rtl')
                <div class="container-fluid">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
        @elseif (\Request::is('profile'))
            @include('layouts.navbars.auth.sidebar')
            <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
                @include('layouts.navbars.auth.nav')
                @yield('content')
            </div>
        @elseif (\Request::is('virtual-reality'))
            @include('layouts.navbars.auth.nav')
            <div class="border-radius-xl mt-3 mx-3 position-relative"
                style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
                @include('layouts.navbars.auth.sidebar')
                <main class="main-content mt-1 border-radius-lg">
                    @yield('content')
                </main>
            </div>
            @include('layouts.footers.auth.footer')
        @else
            @include('layouts.navbars.auth.sidebar')
            <main
                class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ Request::is('rtl') ? 'overflow-hidden' : '' }}">
                @include('layouts.navbars.auth.nav')
                <div class="container-fluid">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
        @endif

        @include('components.fixed-plugin')
    @endif
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>


    @stack('scripts') {{-- This will yield the scripts section --}}



@endsection
