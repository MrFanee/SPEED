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
        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
          <a href="{{ route('twodays.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>

          <a href="{{ route('twodays.upload') }}" class="btn btn-sm btn-success">Upload CSV</a>
        </div>

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif


        <table class="table table-bordered table-striped small" id="twodaysTable">
          <thead class="text-center">
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
                <td>{{ $t->part->item_code }}</td>
                <td>{{ $t->part->part_name }}</td>
                <td>{{ $t->std_stock }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('twodays.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('twodays.delete', $t->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin mau hapus data ini?')">
                        Hapus
                      </button>
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

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const table = document.querySelector("#twodaysTable");
      if (table) {
        new simpleDatatables.DataTable(table);
      }
    });
  </script>
@endsection