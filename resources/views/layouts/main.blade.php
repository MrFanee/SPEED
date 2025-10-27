<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Vendor CSS -->
    <link href="{{ asset('NiceAdmin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('NiceAdmin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('NiceAdmin/css/style.css') }}" rel="stylesheet">

</head>

<body>
    @include('partials.header')
    @include('partials.sidebar')

    <main id="main" class="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Vendor JS -->
    <script src="{{ asset('NiceAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('NiceAdmin/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('NiceAdmin/js/main.js') }}"></script>
</body>

</html>