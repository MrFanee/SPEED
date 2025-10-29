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

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form action="{{ route('stock.upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="file" class="form-label">Pilih file CSV</label>
            <input type="file" name="file" id="file" accept=".csv" class="form-control" required>
            {{-- <div class="form-text">Format header wajib: <code>item_code,std_stock</code></div> --}}
          </div>

          <button type="submit" class="btn btn-success">
            <i class="bi bi-upload"></i> Upload
          </button>
          <a href="{{ route('po.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
      </div>
    </div>
  </section>
@endsection
