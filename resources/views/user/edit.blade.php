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

                        <input type="text" class="form-control" id="username" name="username" value="{{ $users->username }}">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>

                        @error('role')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror
                        
                        <select name="role" id="role" class="form-select">
                            <option value="admin" {{ old('role', $users->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role', $users->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="vendor" {{ old('role', $users->role) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vendor_id">Vendor</label>

                        @error('vendor_id')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <select name="vendor_id" id="vendor_id" class="form-select">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach ($vendorList as $vendor)
                                <option value="{{ $vendor->id }}" 
                                    {{ $users->vendor_id == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->vendor_name }} 
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Password Baru (opsional)</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </section>
@endsection