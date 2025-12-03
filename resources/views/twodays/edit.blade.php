@extends('layouts.main')

@section('title', 'Edit Standar Stok')

@section('content')
  <div class="pagetitle">
    <h1>Edit Standar Stok</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('twodays.index') }}">Standar Stok</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Edit Data Standar Stok</h5>

        <form action="{{ route('twodays.update', $twodays->id) }}" method="POST" class="small">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="std_stock" class="form-label">Standar Stok</label>

            @error('std_stock')
              <div class="text-danger mb-1">{{ $message }}</div>
            @enderror

            <input type="text" class="form-control" id="std_stock" name="std_stock" value="{{ $twodays->std_stock }}">
          </div>

          <button type="submit" class="btn btn-sm btn-outline-success">Update</button>
          <a href="{{ route('twodays.index') }}" class="btn btn-sm btn-outline-secondary">Batal</a>
        </form>
      </div>
    </div>
  </section>
@endsection