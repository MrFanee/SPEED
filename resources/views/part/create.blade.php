@extends('layouts.main')

@section('title', 'Tambah Part')

@section('content')
  <div class="pagetitle">
    <h1>Tambah Part</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('part.index') }}">Part</a></li>
        <li class="breadcrumb-item active">Tambah</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Form Tambah Part</h5>

        <form action="{{ route('part.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="item_code" class="form-label">Item Code</label>
            <input type="item_code" name="item_code" class="form-control" id="item_code" value="{{ old('item_code') }}">
            @error('item_code') <small class="text-danger">{{ $message }}</small> @enderror
          </div>
          
          <div class="mb-3">
            <label for="part_number" class="form-label">Part Number</label>
            <input type="text" name="part_number" class="form-control" id="part_number" value="{{ old('part_number') }}">
            @error('part_number') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label for="part_name" class="form-label">Part Name</label>
            <input type="text" name="part_name" class="form-control" id="part_name" value="{{ old('part_name') }}">
            @error('part_name') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
          <a href="{{ route('part.index') }}" class="btn btn-sm btn-secondary">Batal</a>
        </form>

      </div>
    </div>
  </section>
@endsection