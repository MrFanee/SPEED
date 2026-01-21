@extends('layouts.main')

@section('title', 'Upload CSV DI')

@section('content')
  <div class="pagetitle">
    <h1>Upload CSV DI</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('di.index') }}">DI</a></li>
        <li class="breadcrumb-item active">Upload CSV</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Upload File CSV</h5>

        @if(session('error'))
          <div class="text-danger mb-2 fw-bold">{{ session('error') }}</div>
        @endif

        <form action="{{ route('di.upload') }}" method="POST" enctype="multipart/form-data" class="small">
          @csrf
          <div class="mb-3">
            <label for="file" class="form-label">Format kolom CSV harus: Delivery Date | Item Code | Part Name | No. PO | Qty DI | OS DI | Manifest H</label>
           
            @error('file')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            @if(session('error'))
              <div class="text-danger mb-1">{{ session('error') }}</div>
            @endif

            <input type="file" name="file" id="file" accept=".csv" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-sm btn-outline-success">
            <i class="bi bi-upload"></i> Upload
          </button>
          <a href="{{ route('di.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
        </form>
      </div>
    </div>
  </section>
@endsection