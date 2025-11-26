@extends('layouts.main')

@section('title', 'Upload CSV Stock')

@section('content')
  <div class="pagetitle">
    <h1>Upload CSV Stock</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Stock</a></li>
        <li class="breadcrumb-item active">Upload CSV</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Upload File CSV</h5>

        <form action="{{ route('stock.upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="file" class="form-label">Format kolom CSV harus: Item Code | Part Name |RM | WIP| FG | Kategori Problem | Detail Problem | Kode Vendor</label>
            
            @error('file')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            @if(session('error'))
              <div class="text-danger mb-1">{{ session('error') }}</div>
            @endif

            <input type="file" name="file" id="file" accept=".csv" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-sm btn-success">
            <i class="bi bi-upload"></i> Upload
          </button>
          <a href="{{ route('po.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </form>
      </div>
    </div>
  </section>
@endsection
