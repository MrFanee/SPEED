@php
    $role = auth()->user()->role;
@endphp

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav d-flex flex-column h-100" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard*') ? 'active' : 'collapsed' }}"
                href="{{ route('dashboard')}}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- MASTER DATA --}}
        @if($role == 'admin' || $role == 'staff')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendor*') || request()->is('part*') || request()->is('twodays*') || request()->is('po*') || request()->is('di*') || request()->is('upload_failure*') ? '' : 'collapsed'}}"
                    data-bs-target="#masterdata-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-database"></i>
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="masterdata-nav"
                    class="nav-content collapse {{ request()->is('vendor*') || request()->is('part*') || request()->is('twodays*') || request()->is('po*') || request()->is('di*') || request()->is('upload_failure*') ? 'show' : '' }}"

                    @if($role == 'admin')
                        <li>
                            <a href="{{ route('user.index') }}" class="{{ request()->is('user*') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>User</span>
                            </a>
                        </li>                            
                    @endif

                    <li>
                        <a href="{{ route('vendor.index') }}" class="{{ request()->is('vendor*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Vendor</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('part.index') }}" class="{{ request()->is('part*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Part</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('twodays.index') }}" class="{{ request()->is('twodays*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Master 2HK</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('po.index') }}" class="{{ request()->is('po*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Master PO</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('di.index') }}" class="{{ request()->is('di*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Master DI vs GR</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('upload_failure.index') }}" class="{{ request()->is('upload_failure*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Upload Failed</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- VENDOR: hanya boleh lihat po + di --}}
        @if($role == 'vendor')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('part*') || request()->is('po*') || request()->is('di*') ? '' : 'collapsed' }}"
                    data-bs-target="#masterdata-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-database"></i>
                    <span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="masterdata-nav"
                    class="nav-content collapse {{ request()->is('vendor*') || request()->is('part*') || request()->is('twodays*') || request()->is('po*') || request()->is('di*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">

                    <li>
                        <a href="{{ route('po.index') }}" class="{{ request()->is('po*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Master PO</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('di.index') }}" class="{{ request()->is('di*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Master DI vs PO</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link {{ request()->is('stock*') ? 'active' : 'collapsed' }}" href="{{route('stock.index')}}">
                <i class="bi bi-card-checklist"></i>
                <span>2 Days Stock</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('report*') ? '' : 'collapsed' }}" data-bs-target="#laporan-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laporan</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="laporan-nav" class="nav-content collapse {{ request()->is('report*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                {{-- vendor hanya monthly + yearly --}}
                @if($role != 'vendor')
                    <li>
                        <a href="{{ route('report.vendor') }}"
                            class="{{ request()->is('report/vendor*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Daily</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('report.monthly') }}"
                        class="{{ request()->is('report/monthly*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Monthly</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report.yearly') }}"
                        class="{{ request()->is('report/yearly*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Yearly</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item mt-auto">
            <a class="nav-link {{ request()->is('logout*') ? 'active' : 'collapsed' }}" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</aside>