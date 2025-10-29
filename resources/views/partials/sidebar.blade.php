<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('dashboard')}}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('vendor*') || request()->is('part*') || request()->is('twodays*') || request()->is('po*') || request()->is('di*') ? '' : 'collapsed' }}"
                data-bs-target="#masterdata-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-database"></i>
                <span>Master Data</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="masterdata-nav"
                class="nav-content collapse {{ request()->is('vendor*') || request()->is('part*') || request()->is('twodays*') || request()->is('po*') || request()->is('di*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

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
                    <a href="{{ route('twodays.index') }}"
                        class="{{ request()->is('twodays*') ? 'active' : '' }}">
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
                        <i class="bi bi-circle"></i><span>Master DI</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{route('stock.index')}}">
                <i class="bi bi-card-checklist"></i>
                <span>2 Days Stock</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{('report')}}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laporan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{('login')}}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</aside>