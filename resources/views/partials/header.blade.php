<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between" style="max-width: 230px;">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ asset('images/logo tch no bg.png') }}" alt="logo">
            <span class="d-none d-lg-block">SPEED</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>

            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-block ps-2">
                        {{ auth()->user()->role === 'vendor' ? optional(auth()->user()->vendor)->vendor_name : auth()->user()->username }}
                    </span>
                </a>
            </li>

        </ul>
    </nav>

</header>