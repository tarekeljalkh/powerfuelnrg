<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">NRG</a>
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


            <li class="menu-header">Commands</li>

            {{-- <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i> <span>Layout</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="layout-default.html">Default Layout</a></li>
                    <li><a class="nav-link" href="layout-transparent.html">Transparent Sidebar</a></li>
                    <li><a class="nav-link" href="layout-top-navigation.html">Top Navigation</a></li>
                </ul>
            </li> --}}
            <li class="{{ request()->routeIs('clients.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('clients.index') }}"><i class="fas fa-users"></i>
                    <span>Clients</span></a></li>

                    {{--

            <li class="{{ request()->routeIs('admin.labs.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin.labs.index') }}"><i class="fas fa-flask"></i>
                    <span>{{ trans('messages.labs') }}</span></a></li>

            <li class="{{ request()->routeIs('admin.external_labs.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin.external_labs.index') }}"><i class="fas fa-vials"></i>
                    <span>{{ trans('messages.external_labs') }}</span></a></li>

            <li class="{{ request()->routeIs('admin.type-of-works.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin.type-of-works.index') }}"><i class="fas fa-vials"></i>
                    <span>{{ trans('messages.type_of_works') }}</span></a></li>

            <li class="{{ request()->routeIs('admin.setting.index') ? 'active' : '' }}"><a class="nav-link"
                    href="{{ route('admin.setting.index') }}"><i class="fas fa-cog"></i>
                    <span>{{ trans('messages.settings') }}</span></a></li> --}}


        </ul>

    </aside>
</div>
