@extends('layouts.main')

@section('title', 'Edit Vendor')

@section('content')
  <div class="pagetitle">
    <h1>Edit Vendor</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Edit Data Vendor</h5>

        <form action="{{ route('vendor.update', $vendors->id) }}" method="POST" class="small">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>

            @error('nickname')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ $vendors->nickname }}">
          </div>

          <div class="mb-3">
            <label for="vendor_name" class="form-label">Vendor Name</label>

            @error('vendor_name')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <input type="text" class="form-control" id="vendor_name" name="vendor_name"
              value="{{ $vendors->vendor_name }}">
          </div>

          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>

            @error('alamat')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $vendors->alamat }}</textarea>
          </div>

          <button type="submit" class="btn btn-sm btn-outline-success">Update</button>
          <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
        </form>
      </div>
    </div>
  </section>
@endsection