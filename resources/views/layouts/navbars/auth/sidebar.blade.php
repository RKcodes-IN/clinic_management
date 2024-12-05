<style>
    /* .dropdown:not(.dropdown-hover) .dropdown-menu {
        margin-top: 0 !important;
        position: absolute;


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
    .sidenav .dropdown-menu {
        max-height: 0;
        /* Initially hidden */
        opacity: 0;
        /* Initially transparent */
        overflow: hidden;
        /* Prevent content overflow */
        margin-top: -41px;
        margin-bottom: 28px;
        margin-left: 30px !important;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        /* Smooth transition */
    }


    @media (max-width: 767.98px) {
        .sidenav .dropdown-menu {
            margin-top: -8px;
            margin-bottom: 8px;
        }
    }

    /* Style for visible submenus */
    .sidenav .dropdown-menu.active {

        max-height: 500px;
        /* Adjust as needed to fit your submenu's max height */
        opacity: 1;
        /* Fully visible */
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

            @php
                $role = auth()->user();

            @endphp


            @can(['create appointment'])
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                        role="button" data-bs-toggle="dropdown" data-target="#appointmentsMenu" aria-expanded="false">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-unlock-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Appointments</span>
                    </a>
                    <ul class="collapse dropdown-menu" id="appointmentsMenu">

                        @can(['create appointment', 'edit appointment'])
                            <li><a class="dropdown-item" href="{{ route('appointments.create') }}">New Appointment</a></li>
                            <li><a class="dropdown-item" href="{{ route('appointments.create') }}">Appointment Reports</a></li>
                        @endcan


                        @can('read appointment')

                            <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Appointment List</a></li>
                            @if ($role->hasRole('doctor'))
                                <li><a class="dropdown-item" href="{{ route('appointent.calander') }}">Calendar</a></li>
                            @endif
                        @endcan

                        <li><a class="dropdown-item" href="{{ route('investigationreport.create') }}">Upload Investigation
                                Report</a></li>
                    </ul>
                </li>
            @endcan
            @can('read paitent')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                        role="button" data-bs-toggle="dropdown" data-target="#paitentdropdown" aria-expanded="false">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-unlock-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Paitent's</span>
                    </a>


                    <ul class="dropdown-menu" id="paitentdropdown">
                        @can(['read paitent', 'edit paitent', 'create paitent'])
                            <li><a class="dropdown-item" href="{{ route('paitent.index') }}">Paitent List</a></li>
                        @endcan


                        @can(['read healthevalution', 'create healthevalution', 'edit healthevalution'])
                            <li><a class="dropdown-item" href="{{ route('healthevalution.create') }}">Create Health Evalution
                                    Sheet</a>
                            </li>
                        @endcan
                        @can('read healthevalution')
                            <li><a class="dropdown-item" href="{{ route('healthevalution.index') }}">Patient Health Evalutions
                                </a>
                            </li>
                        @endcan
                        @can('create invetigationreport')
                            <li><a class="dropdown-item" href="{{ route('investigationreport.create') }}">Upload Investigation
                                    Report</a></li>
                        @endcan

                        {{-- <li><a class="dropdown-item" href="{{ route('appointments.index') }}">Add New Paitent</a>
            </li> --}}
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

            @endcan
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Doctor's Management</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" data-target="#doctordropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Doctor</span>
                </a>
                <ul class="dropdown-menu" id="doctordropdown">
                    @can('create doctordetail')
                        <li><a class="dropdown-item" href="{{ Route('doctorDetail.create') }}">Create Doctor</a></li>
                    @endcan
                    @can('read doctordetail')
                        <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a></li>
                    @endcan
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>


            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Inventroy</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" data-target="#inventorydeopdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Inventory</span>
                </a>
                <ul class="dropdown-menu" id="inventorydeopdown">
                    <li><a class="dropdown-item" href="{{ Route('items.index') }}">Items List</a></li>
                    <li><a class="dropdown-item" href="{{ Route('items.create') }}">Add Items</a></li>
                    <li><a class="dropdown-item" href="{{ Route('purchaseorder.index') }}">Purchase Order</a></li>
                    <li><a class="dropdown-item" href="{{ Route('stock.index') }}">Stock</a></li>
                    {{-- <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a>
        </li> --}}
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" data-target="#invoicedropdown">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Invoice</span>
                </a>
                <ul class="dropdown-menu" id="invoicedropdown">
                    <li><a class="dropdown-item" href="{{ Route('items.index') }}">Create Invoice</a></li>
                    <li><a class="dropdown-item" href="{{ Route('items.create') }}">Invoice List</a></li>
                    {{-- <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a>
        </li> --}}
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>

            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Admin Settings</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" data-target="#adminsettingdropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin Settings</span>
                </a>
                <ul class="dropdown-menu" id="adminsettingdropdown">
                    @can('create users')
                        <li><a class="dropdown-item" href="{{ Route('users.create') }}">Users Create</a></li>
                    @endcan
                    @can('read users')
                        <li><a class="dropdown-item" href="{{ Route('users.index') }}">Users List</a></li>
                    @endcan

                    @can('read roles')
                        <li><a class="dropdown-item" href="{{ Route('roles.index') }}">Roles</a></li>
                    @endcan
                </ul>
            </li>

            @can('read variables')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                        role="button" data-bs-toggle="dropdown" data-target="#variabledropdown" aria-expanded="false">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-unlock-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Variables Settings</span>
                    </a>
                    <ul class="dropdown-menu" id="variabledropdown">
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
            @endcan

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" data-target="#invoicedropdown">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dummy</span>
                </a>


            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" data-target="#invoicedropdown">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-unlock-alt"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dummy</span>
                </a>

            </li>
        </ul>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Select all parent menu items with dropdowns
        const dropdownToggles = document.querySelectorAll('.nav-link.dropdown-toggle');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent default anchor behavior

                // Find the target submenu
                const targetMenu = document.querySelector(toggle.getAttribute('data-target'));

                if (targetMenu) {
                    // Toggle the 'active' class
                    const isActive = targetMenu.classList.contains('active');
                    targetMenu.classList.toggle('active', !isActive);

                    // Ensure max-height is reset when closing for smooth transition
                    if (!isActive) {
                        targetMenu.style.maxHeight = targetMenu.scrollHeight +
                            'px'; // Set to the full height
                    } else {
                        targetMenu.style.maxHeight = '0'; // Collapse smoothly
                    }
                } else {
                    console.error('Target submenu not found:', toggle.getAttribute(
                        'data-target'));
                }
            });
        });
    });
</script>
