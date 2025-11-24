@extends('layouts.main')

@section('title', 'Tambah User')

@section('content')
    <div class="pagetitle">
        <h1>Tambah User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Tambah User</h5>

                <form action="{{ route('user.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" id="password" value="{{ old('password') }}">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select">
                            <option value="" selected disabled>-- Pilih --</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="vendor">Vendor</option>
                        </select>
                        @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </section>
@endsection