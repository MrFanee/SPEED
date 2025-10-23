<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('dashboard')}}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-database"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link " href="{{ route('vendor.index')}}">
                        <i class="bi bi-circle"></i><span>Vendor</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link " href="{{ route('part.index')}}">
                        <i class="bi bi-circle"></i><span>Part</span>
                    </a>
                </li>
                <li>
                    <a href="components-badges.html">
                        <i class="bi bi-circle"></i><span>Data</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{('stock')}}">
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