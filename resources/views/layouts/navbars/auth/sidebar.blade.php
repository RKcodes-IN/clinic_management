<style>
    /* Consolidated dropdown styles */
    .sidenav .dropdown-menu {
        --transition-duration: 0.3s;
        --transition-easing: ease;

        max-height: 0;
        opacity: 0;
        overflow: hidden;
        margin: -41px 0 28px 30px;
        transition: all var(--transition-duration) var(--transition-easing);
    }

    @media (max-width: 767.98px) {
        .sidenav .dropdown-menu {
            margin: -8px 0 8px 30px;
        }
    }

    .sidenav .dropdown-menu.active {
        max-height: 500px;
        opacity: 1;
    }

    /* Simplified header styles */
    .navbar-nav .nav-item {
        margin-bottom: 0.5rem;
    }

    .navbar-nav h6 {
        margin: 1rem 0 0.5rem;
    }
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
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <span class="nav-link-text ms-1">Appointments</span>
                    </a>
                    <ul class="collapse dropdown-menu" id="appointmentsMenu">

                        @can(['create appointment', 'edit appointment'])
                            <li><a class="dropdown-item" href="{{ route('appointments.create') }}">New Appointment</a></li>
                            <li><a class="dropdown-item" href="{{ route('appointments.wa') }}">Appointment (WA)</a></li>
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
                            <i class="fa-solid fa-square-plus"></i>
                        </div>
                        <span class="nav-link-text ms-1">Patients</span>
                    </a>


                    <ul class="dropdown-menu" id="paitentdropdown">
                        @can(['read paitent', 'edit paitent', 'create paitent'])
                            <li><a class="dropdown-item" href="{{ route('paitent.index') }}">Patient List</a></li>
                        @endcan


                        @can(['read healthevalution', 'create healthevalution', 'edit healthevalution'])
                            <li><a class="dropdown-item" href="{{ route('healthevalution.create') }}">Create Health Evaluation
                                    Sheet</a>
                            </li>
                        @endcan
                        @can('read healthevalution')
                            <li><a class="dropdown-item" href="{{ route('healthevalution.index') }}">Patient Health Evaluations
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
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pharmacy</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" data-target="#inventorydeopdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-warehouse"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pharmacy</span>
                </a>
                <ul class="dropdown-menu" id="inventorydeopdown">
                    <li><a class="dropdown-item" href="{{ Route('items.index', ['item_type' => 1]) }}">Items List</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Route('items.create') }}">Add Items</a></li>
                    <li><a class="dropdown-item" href="{{ Route('purchaseorder.index') }}">Purchase Order</a></li>
                    <li><a class="dropdown-item" href="{{ route('stock.index', ['item_type' => 1]) }}">Stock</a></li>
                    <li><a class="dropdown-item" href="{{ Route('stock.filterview') }}">Pharmacy Transactions</a>
                    <li><a class="dropdown-item" href="{{ Route('stock.filte.report') }}">Pharmacy Stock Report</a>
                    </li>
                    {{-- <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a>
        </li> --}}
                    <!-- Add more submenu items as needed -->
                </ul>
            </li>
            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Lab</h6>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" data-target="#lab" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-warehouse"></i>
                    </div>
                    <span class="nav-link-text ms-1">Labs</span>
                </a>
                <ul class="dropdown-menu" id="lab">
                    <li><a class="dropdown-item" href="{{ Route('items.index', ['item_type' => 3]) }}">Items List</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Route('items.create', ['type' => 'lab']) }}">Add Items</a>
                    </li>
                    @can('edit stock')
                        <li><a class="dropdown-item" href="{{ route('stock.updatepricing', ['type' => 'lab']) }}">Update
                                Pricing</a></li>
                    @endcan

                    <li><a class="dropdown-item" href="{{ Route('labprescription.index') }}">Investigations</a>
                    </li>
                    {{-- <li><a class="dropdown-item" href="{{ Route('stock.index', ['item_type' => 3]) }}">Stock</a></li> --}}
                    {{-- <li><a class="dropdown-item" href="{{ Route('stock.filte.report') }}">Pharmacy Stock Report</a> --}}
            </li>
            {{-- <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a>
        </li> --}}
            <!-- Add more submenu items as needed -->
        </ul>
        </li>

        <li class="nav-item mt-2">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Miscellaneous</h6>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                role="button" data-bs-toggle="dropdown" data-target="#miscellaneous" aria-expanded="false">
                <div
                    class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
                <span class="nav-link-text ms-1">Miscellaneous</span>
            </a>
            <ul class="dropdown-menu" id="miscellaneous">
                <li><a class="dropdown-item" href="{{ Route('items.index', ['item_type' => 2]) }}">Items List</a>
                </li>
                <li><a class="dropdown-item" href="{{ Route('items.create', ['type' => 'miss']) }}">Add Items</a>
                </li>
                {{-- <li><a class="dropdown-item" href="{{ Route('stock.index', ['item_type' => 2]) }}">Stock</a></li> --}}
                @can('edit stock')
                    <li><a class="dropdown-item" href="{{ route('stock.updatepricing', ['type' => 'mis']) }}">Update
                            Pricing</a></li>
                @endcan
                {{-- <li><a class="dropdown-item" href="{{ Route('doctorDetail.index') }}">Doctor's List</a>
        </li> --}}
                <!-- Add more submenu items as needed -->
            </ul>
        </li>
        <li class="nav-item mt-2">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Invoice</h6>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false" data-target="#invoicedropdown">
                <div
                    class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <span class="nav-link-text ms-1">Invoice</span>
            </a>
            <ul class="dropdown-menu" id="invoicedropdown">
                <li><a class="dropdown-item" href="{{ Route('invoice.create') }}">Create Invoice</a></li>
                <li><a class="dropdown-item" href="{{ Route('invoice.index') }}">Invoice List</a></li>

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
                    <i class="fa-solid fa-gear"></i>
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

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                role="button" data-bs-toggle="dropdown" data-target="#doctordropdown" aria-expanded="false">
                <div
                    class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-user-doctor"></i>
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

        </li>

        @can('read variables')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('settings/*') ? 'active' : '' }}" href="#"
                    role="button" data-bs-toggle="dropdown" data-target="#variabledropdown" aria-expanded="false">
                    <div
                        class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-list-check"></i>
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

                    <li><a class="dropdown-item" href="{{ Route('sample-types.index') }}">Sample Type</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ Route('surgical-variables.index') }}">Surgical</a>
                    </li>

                    <li><a class="dropdown-item" href="{{ Route('habit-variables.index') }}">Habits</a>
                    </li>

                    <!-- Add more submenu items as needed -->
                </ul>
            </li>
        @endcan
        <li class="nav-item mt-2">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Dummy 1</h6>
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

        <li class="nav-item mt-2">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Dummy 2</h6>
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
    document.addEventListener('click', function(event) {
        // Check if the clicked element is not inside the sidenav or dropdown menu
        if (!event.target.closest('.sidenav') && !event.target.closest('.dropdown-menu')) {
            // Find all open dropdown menus
            const openMenus = document.querySelectorAll('.sidenav .dropdown-menu.active');
            openMenus.forEach(menu => {
                // Remove 'active' class and reset max-height
                menu.classList.remove('active');
                menu.style.maxHeight = null;
                menu.style.opacity = 0;
            });
        }
    });

    // Handle toggling of dropdown menus
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(event) {
            event.preventDefault();

            // Find the associated dropdown menu
            const menu = this.nextElementSibling;

            if (menu.classList.contains('active')) {
                // Collapse menu if already open
                menu.classList.remove('active');
                menu.style.maxHeight = null;
                menu.style.opacity = 0;
            } else {
                // Close other open menus
                document.querySelectorAll('.sidenav .dropdown-menu.active').forEach(openMenu => {
                    openMenu.classList.remove('active');
                    openMenu.style.maxHeight = null;
                    openMenu.style.opacity = 0;
                });

                // Expand the clicked menu
                menu.classList.add('active');
                menu.style.maxHeight = menu.scrollHeight + 'px'; // Adjust height dynamically
                menu.style.opacity = 1;
            }
        });
    });
</script>
