<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('images/logo trimitra.png') }}" style="width: 100px; height: 50px;">

    <!-- Vendor CSS -->
    <link href="{{ asset('NiceAdmin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('NiceAdmin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('NiceAdmin/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="{{ asset('NiceAdmin/vendor/simple-datatables/style.css') }}" rel="stylesheet">
</head>

<body>
    @include('partials.header')
    @include('partials.sidebar')

    <main id="main" class="main">
        @yield('searchbar')

        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Vendor JS -->
    <script src="{{ asset('NiceAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('NiceAdmin/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('NiceAdmin/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>