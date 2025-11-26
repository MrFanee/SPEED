@extends('layouts.main')

@section('title', 'Tambah Vendor')

@section('content')
  <div class="pagetitle">
    <h1>Tambah Vendor</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
        <li class="breadcrumb-item active">Tambah</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Form Tambah Vendor</h5>

        <form action="{{ route('vendor.store') }}" method="POST" class="small">
          @csrf

          <div class="mb-3">
            <label for="kode_vendor" class="form-label">Kode Vendor</label>

            @error('kode_vendor')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <input type="text" name="kode_vendor" class="form-control" id="kode_vendor" value="{{ old('kode_vendor') }}">
          </div>

          <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>

            @error('nickname')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <input type="text" name="nickname" class="form-control" id="nickname" value="{{ old('nickname') }}">
          </div>

          <div class="mb-3">
            <label for="vendor_name" class="form-label">Nama Vendor</label>

            @error('vendor_name')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <input type="text" name="vendor_name" class="form-control" id="vendor_name" value="{{ old('vendor_name') }}">
          </div>

          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>

            @error('alamat')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <textarea name="alamat" class="form-control" id="alamat">{{ old('alamat') }}</textarea>
          </div>

          <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
          <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-secondary">Batal</a>
        </form>

      </div>
    </div>
  </section>
@endsection