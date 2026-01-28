<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        .dashboard-topbar {
            background-color: white;
            color: #213555;
            padding: 0.5rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .dashboard-topbar img {
            height: 40px;
        }

        .dashboard-topbar h5 {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        @media (max-width: 576px) {
            .dashboard-topbar h5 {
                font-size: 0.9rem;
            }

            .dashboard-topbar img {
                height: 30px;
            }
        }
    </style>
</head>

<body>
    <!-- ===== TOPBAR ===== -->
    <div class="dashboard-topbar d-flex align-items-center justify-content-between">
        <img src="{{ asset('images/logo tch no bg.png') }}" alt="logo">

        <div class="d-flex align-items-center">
            <h3 class="fw-bold">MONITORING 2 DAYS STOCK</h3>
        </div>

        <div>
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    <!-- ===== CONTENT ===== -->
    <div class="container-fluid mt-3">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <!-- ===== FOOTER ===== -->
    <footer class="text-center text-muted py-2 mt-3">
        <div class="container">
            &copy; Copyright <strong><span>TRIMITRA CHITRAHASTA</span></strong>. All Rights Reserved
        </div>
    </footer>

</body>

</html>