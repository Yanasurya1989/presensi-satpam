<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
        </div>
        <div class="sidebar-brand-text mx-3">Mutaba'ah yaumiyah</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            {{-- <i class="fas fa-solid fa-house"></i> --}}
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider my-0">

    <?php
    $role = Auth::user()->role->name;
    ?>
    @if ($role == 'Super Admin' || $role == 'Admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/users') }}">
                {{-- <i class="fas fa-fw fa-tachometer-alt"></i> --}}
                <i class="fas fa-solid fa-user"></i>
                <span>Users</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
    @else
        <p style="display: none">Anda tidak bisa mengakses halaman ini</p>
    @endif

    <!-- Heading -->
    <div class="sidebar-heading pt-3">
        Menu
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Mutaba'ah</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <?php
                $role = Auth::user()->role->name;
                ?>
                @if ($role == 'User')
                    <a class="collapse-item" href="{{ url('/report') }}">Personal Report</a>
                @else
                    <p style="display: none">Anda tidak bisa mengakses halaman ini</p>
                @endif
                {{-- selain user --}}
                <?php
                $role = Auth::user()->role->name;
                ?>
                @if ($role == 'Super Admin' || $role == 'Admin')
                    <a class="collapse-item" href="{{ url('/admin_view') }}">Personal Report</a>
                @else
                    <p style="display: none">Anda tidak bisa mengakses halaman ini</p>
                @endif

                <a class="collapse-item" href="{{ url('/insert') }}">Insert</a>
                <?php
                $role = Auth::user()->role->name;
                ?>
                @if ($role == 'Super Admin' || $role == 'Admin')
                    {{-- <a class="collapse-item" href="{{ url('/admin/rekap') }}">Admin</a> --}}
                @else
                    <p style="display: none">Anda tidak bisa mengakses halaman ini</p>
                @endif
                <?php
                $role = Auth::user()->role->name;
                ?>
                @if ($role == 'Super Admin')
                    {{-- @if ($role == 'Super Admin' || $role == 'Admin') --}}
                    <a class="collapse-item" href="{{ url('/report') }}">Daily Check</a>
                    <a class="collapse-item" href="{{ url('/naon') }}">All User Report</a>
                @else
                    <p style="display: none">Anda tidak bisa mengakses halaman ini</p>
                @endif
            </div>
        </div>
    </li>
    <?php
    $role = Auth::user()->role->name;
    ?>
    @if ($role == 'Super Admin')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                {{-- <i class="fas fa-fw fa-cog"></i> --}}
                <i class="fas fa-sharp-duotone fa-regular fa-clock"></i>
                <span>Presensi</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ url('/presensi-sc') }}">Presensi Masuk</a>
                    <a class="collapse-item" href="{{ url('/presensi-keluar') }}">Presensi Pulang</a>
                    <a class="collapse-item" href="{{ url('/filter-data') }}">Rekap Presensi</a>
                    <a class="collapse-item" href="{{ url('/shiftsforschedule') }}">Shift</a>
                    <a class="collapse-item" href="{{ url('/shifts') }}">Pembagian Shift</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
    @else
        <p style="display: none">Anda tidak bisa mengakses halaman ini</p>
    @endif


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
