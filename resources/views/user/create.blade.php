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

                <form action="{{ route('user.store') }}" method="POST" class="small">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>

                        @error('username')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        
                        @error('password')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <input type="text" name="password" class="form-control" id="password" value="{{ old('password') }}">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        
                        @error('role')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <select name="role" id="role" class="form-select">
                            <option value="" selected disabled>-- Pilih --</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="vendor">Vendor</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="vendor-wrapper">
                        <label for="vendor_id">Vendor</label>

                        @error('vendor_id')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <select name="vendor_id" id="vendor_id" class="form-select">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach ($vendor as $v)
                                <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-outline-primary">Simpan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
                </form>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const vendorWrapper = document.getElementById('vendor-wrapper');

            function toggleVendor() {
                if (roleSelect.value === 'vendor') {
                    vendorWrapper.classList.remove('d-none');
                } else {
                    vendorWrapper.classList.add('d-none');
                    document.getElementById('vendor_id').value = '';
                }
            }

            roleSelect.addEventListener('change', toggleVendor);

            toggleVendor();
        });
    </script>

@endsection