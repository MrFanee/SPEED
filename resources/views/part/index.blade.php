@extends('layouts.main')

@section('title', 'Data Part')

@section('content')
  <div class="pagetitle">
    <h1>Data Part</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Part</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mt-3">
          <a href="{{ route('part.create') }}" class="btn btn-sm btn-outline-primary mb-3">
            <i class="bi bi-plus-circle-dotted"></i> Tambah
          </a>
          {{-- <a href="{{ route('po.upload') }}" class="btn btn-sm btn-success">Upload CSV</a> --}}
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

        <table class="table table-bordered table-striped small" id="partTable">
          <thead class="text-center">
            <tr>
              <th>#</th>
              <th>Item Code</th>
              <th>Part Number</th>
              <th>Part Name</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($parts as $p)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->item_code }}</td>
                <td>{{ $p->part_number }}</td>
                <td>{{ $p->part_name }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('part.edit', $p->id) }}" class="btn btn-outline-warning btn-sm">
                      <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('part.delete', $p->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Yakin mau hapus data ini?')">
                        <i class="bi bi-trash-fill"></i>
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
      const table = document.querySelector("#partTable");
      if (table) {
        new simpleDatatables.DataTable(table);
      }
    });
  </script>

@endsection