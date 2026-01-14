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

            <!-- LEFT SIDE -->
           <div class="col-md-7 d-none d-md-flex text-white align-items-center justify-content-center flex-column position-relative"
                style="
                    background-image: url('{{ asset('images/tch.jpeg') }}');
                    background-size: cover;
                    background-position: center;
                ">

                <div class="position-absolute top-0 start-0 w-100 h-100"
                    style="background: rgba(0,0,0,0.35);"></div>

                <div class="position-relative text-center">
                    <h1 class="fw-bold mb-2">SPEED</h1>
                    <p class="opacity-75 px-5">
                        System Procurement Efficiency and Evaluation Dashboard
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-5 d-flex align-items-center justify-content-center">

                <div class="card shadow border-0 rounded-4 w-75">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-1 text-center">Welcome 👋</h4>
                        <p class="text-secondary small text-center mb-4">
                            Silakan masukkan username dan password
                        </p>

                        @if ($errors->any())
                            <div class="text-danger small mb-2 fw-bold">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('login.submit') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-person-fill text-primary"></i>
                                    </span>
                                    <input type="text" name="username" class="form-control" placeholder="Username"
                                        value="{{ old('username') }}">
                                </div>

                                @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-lock-fill text-primary"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>

                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>