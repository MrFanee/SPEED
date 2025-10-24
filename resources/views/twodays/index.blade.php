@extends('layouts.main')

@section('title', 'Data Standar Stok')

@section('content')
  <div class="pagetitle">
    <h1>Data Standar Stok</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Standar Stok</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Daftar Standar Stok</h5>

        <a href="{{ route('twodays.create') }}" class="btn btn-primary mb-3">+ Tambah Standar Stok</a>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif


        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Part Name</th>
              <th>Item Code</th>
              <th>Standar Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($twodays as $t)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->part->part_name }}</td>
                <td>{{ $t->item_code }}</td>
                <td>{{ $t->std_stock }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('twodays.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('twodays.delete', $t->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </section>
@endsection