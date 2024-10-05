<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">PowerfuelNRG</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}"><img style="width: 35px" src="{{ asset('assets/favicon.png') }}"
                    alt="NRG"></a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('dashboard') }}"><i class="fas fa-fire"></i>
                    <span>Dashboard</span></a></li>

            <li class="menu-header">Manage</li>
            <!-- Accounts Section -->
            <li class="{{ request()->routeIs('accounts.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('accounts.index') }}"><i class="fas fa-book"></i>
                    <span>accounts</span></a></li>

            <!-- Currencies Section -->
            <li class="{{ request()->routeIs('currencies.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('currencies.index') }}"><i class="fas fa-coins"></i>
                    <span>currencies</span></a></li>

            <li class="menu-header">Commands</li>

            <!-- New Clients Section -->
            <li class="{{ request()->routeIs('clients.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('clients.index') }}"><i class="fas fa-user-friends"></i>
                    <span>Clients</span></a></li>

            <!-- New Vouchers Section -->
            <li class="{{ request()->routeIs('vouchers.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('vouchers.index') }}"><i class="fas fa-file-invoice-dollar"></i>
                    <span>Vouchers</span></a></li>

            <!-- New Receipts Section -->
            <li class="{{ request()->routeIs('receipts.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('receipts.index') }}"><i class="fas fa-receipt"></i>
                    <span>Receipts</span></a></li>



            <li class="menu-header">Reports</li>

            <!-- Client Balance Report (Filter) -->
            <li class="{{ request()->routeIs('reports.filter') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.filter') }}">
                    <i class="fas fa-chart-line"></i> <span>Client Balance Report</span>
                </a>
            </li>


            <li class="menu-header">Settings</li>

            <li class="dropdown {{ request()->routeIs(['hero.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-fingerprint"></i> <span>Access Management</span></a>

                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs(['role-user.*']) }}"><a class="nav-link"
                            href="{{ route('role-user.index') }}">Roles Users</a></li>

                    <li class="{{ request()->routeIs(['role.*']) }}"><a class="nav-link"
                            href="{{ route('role.index') }}">Roles and Permissions</a></li>
                </ul>
            </li>

        </ul>
    </aside>
</div>
