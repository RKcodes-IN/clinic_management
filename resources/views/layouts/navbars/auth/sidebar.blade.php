<style>
    .dropdown:not(.dropdown-hover) .dropdown-menu {
        margin-top: 0 !important;
        position: absolute;
        /* Ensure dropdown menus appear correctly */
        margin: 14px 0 0 18px !important;
    }

    /* Adjust margin/padding to reduce space between menu sections */
    .navbar-nav .nav-item {
        margin-bottom: 0.5rem;
    }

    /* Adjust padding for section headers */
    .navbar-nav h6 {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    /* Ensure submenus are aligned correctly */
    .dropdown-menu {
        margin-top: 0 !important;
        left: 0 !important;
        /* Ensure it aligns correctly with the parent */
    }

    /* Additional custom styles if needed */
</style>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fa-solid fa-gauge p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
            <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-auto" alt="...">
            <span class="ms-3 font-weight-bold">SIVAS HEALTH & RESEARCH INSTITUTE</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md bg-white text-center d-flex align-items-center justify-content-center">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Appointments</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('appointments.create') }}">New Appointment Form</a></li>
                    <li><a class="dropdown-item" href="{{ route('appointments.create') }}">New Appointment Reports</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('appointments.index') }}">New Appointment List</a></li>
                    <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Calandar</a></li>
                    <li><a class="dropdown-item" href="{{ Route('investigationreport.create') }}">Upload
                            Investigation Report</a>


                        <!-- Add more submenu items as needed -->
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Paitent's</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('paitent.index') }}">Paitent List</a></li>
                    <li><a class="dropdown-item" href="{{ route('healthevalution.create') }}">Health Evalution
                            Sheet</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('healthevalution.index') }}">Patient Health Evalutions
                        </a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('investigationreport.create') }}">Upload Investigation
                            Report</a></li>

                    {{-- <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Add New Paitent</a></li> --}}
                    {{-- <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Add Case Notes</a></li> --}}
                    {{-- <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Add Investigations</a></li>
                    <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Investigations List</a></li>
                    <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Parameters To Review</a></li>
                    <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Add Prescriptions</a></li>
                    <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Prescription List</a></li> --}}
                    {{-- <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Past Medication</a></li> --}}


                    <!-- Add more submenu items as needed -->
                </ul>
            </li>
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Doctor's Management</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Doctor</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ Route('doctorDetail.create') }}">Doctor Create</a></li>
                    <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a></li>
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>


            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Inventroy</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Inventory</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ Route('items.index') }}">Items List</a></li>
                    <li><a class="dropdown-item" href="{{ Route('items.create') }}">Add Items</a></li>
                    {{-- <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a></li> --}}
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>

            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Admin Settings</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin Settings</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ Route('users.index') }}">Users</a></li>
                    <li><a class="dropdown-item" href="{{ Route('users.create') }}">Users Create</a></li>
                    <li><a class="dropdown-item" href="{{ Route('roles.create') }}">Roles</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Variables Settings</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ Route('source-company.index') }}">Source Company</a></li>
                    <li><a class="dropdown-item" href="{{ Route('category.index') }}">Category</a></li>
                    <li><a class="dropdown-item" href="{{ Route('brand.index') }}">Brand</a></li>
                    <li><a class="dropdown-item" href="{{ Route('uomtype.index') }}">UOM Type</a></li>
                    <li><a class="dropdown-item" href="{{ Route('investigationreporttype.index') }}">Investigation
                            Report Type</a>
                    </li>

                    <!-- Add more submenu items as needed -->
                </ul>
            </li>
        </ul>
    </div>
</aside>
