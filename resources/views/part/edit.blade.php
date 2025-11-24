@extends('layouts.main')

@section('title', 'Edit Part')

@section('content')
  <div class="pagetitle">
    <h1>Edit Part</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('part.index') }}">Part</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Edit Data Part</h5>

        <form action="{{ route('part.update', $parts->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="item_code" class="form-label">Item Code</label>
            <textarea class="form-control" id="item_code" name="item_code" rows="3"
              required>{{ $parts->item_code }}</textarea>
          </div>
          
          <div class="mb-3">
            <label for="part_number" class="form-label">Part Number</label>
            <input type="text" class="form-control" id="part_number" name="part_number" value="{{ $parts->part_number }}"
              required>
          </div>

          <div class="mb-3">
            <label for="part_name" class="form-label">Part Name</label>
            <input type="text" class="form-control" id="part_name" name="part_name" value="{{ $parts->part_name }}"
              required>
          </div>

          <button type="submit" class="btn btn-sm btn-success">Update</button>
          <a href="{{ route('part.index') }}" class="btn btn-sm btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </section>
@endsection