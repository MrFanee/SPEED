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

      <form action="{{ route('twodays.update', $twodays->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="std_stock" class="form-label">Standar Stok</label>
          <input type="text" class="form-control" id="std_stock" name="std_stock" value="{{ $twodays->std_stock }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('twodays.index') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</section>
@endsection
