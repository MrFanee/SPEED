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
          <a href="{{ route('part.create') }}" class="btn btn-sm btn-primary mb-3">+ Tambah</a>

          {{-- <a href="{{ route('po.upload') }}" class="btn btn-sm btn-success">Upload CSV</a> --}}
        </div>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif


        <table class="table table-bordered small">
          <thead class="text-center">
            <tr>
              <th>#</th>
              <th>Part Name</th>
              <th>Part Number</th>
              <th>Item Code</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($parts as $p)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->part_name }}</td>
                <td>{{ $p->part_number }}</td>
                <td>{{ $p->item_code }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('part.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('part.delete', $p->id) }}" method="POST">
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