<style>
    .dropdown:not(.dropdown-hover) .dropdown-menu {
        margin-top: -18px !important;
    }
</style>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fa-solid fa-gauge p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
            <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="...">
            <span class="ms-3 font-weight-bold">Doctor Management</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm  border-radius-md bg-white text-center  d-flex align-items-center justify-content-center">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-2">

                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Admin Settings</h6>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon  icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin Settings</span>
                </a>
                <ul class="dropdown-menu mt-0">
                    <li><a class="dropdown-item" href="{{ Route('users.index') }}">Users</a></li>
                    <li><a class="dropdown-item" href="{{ url('settings/security') }}">Roles</a></li>
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>
        </ul>
    </div>
</aside>
