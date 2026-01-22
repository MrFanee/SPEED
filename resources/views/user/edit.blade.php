@extends('layouts.main')

@section('title', 'Edit User')

@section('content')
    <div class="pagetitle">
        <h1>Edit User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Data User</h5>

                <form action="{{ route('user.update', $users->id) }}" method="POST" class="small">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>

                        @error('username')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ $users->username }}">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>

                        @error('role')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <select name="role" id="role" class="form-select">
                            <option value="admin" {{ old('role', $users->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role', $users->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="vendor" {{ old('role', $users->role) == 'vendor' ? 'selected' : '' }}>Vendor
                            </option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="vendor-wrapper">
                        <label for="vendor_id">Vendor</label>

                        @error('vendor_id')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <select name="vendor_id" id="vendor_id" class="form-select">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach ($vendorList as $vendor)
                                <option value="{{ $vendor->id }}" {{ $users->vendor_id == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->vendor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Password Baru (opsional)</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Kosongkan jika tidak diubah">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-sm btn-outline-success">Update</button>
                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const vendorWrapper = document.getElementById('vendor-wrapper');
            const vendorSelect = document.getElementById('vendor_id');

            function toggleVendor() {
                if (roleSelect.value === 'vendor') {
                    vendorWrapper.classList.remove('d-none');
                } else {
                    vendorWrapper.classList.add('d-none');
                    vendorSelect.value = '';
                }
            }

            roleSelect.addEventListener('change', toggleVendor);

            toggleVendor();
        });
    </script>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>


@endsection