<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row min-vh-100">

            <div class="col-md-7 d-none d-md-flex text-white align-items-center justify-content-center flex-column"
                style="background-color: #91C8E4;">
                <h2 class="fw-bold">SPEED</h2>
                <p class="mt-2">Stock Procurement Efficiency and Evaluation Dashboard</p>
                <!-- nanti bisa taruh gambar di sini -->
            </div>

            <div class="col-md-5 d-flex align-items-center justify-content-center">

                <div class="w-75">
                    <h3 class="fw-bold mb-1">Login</h3>
                    <p class="text-secondary small mb-3">Masukkan username dan password!</p>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Username"
                                    value="{{ old('username') }}">
                            </div>

                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if ($errors->has('loginError'))
                                <small class="text-danger">{{ $errors->first('loginError') }}</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>

                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>

</html>