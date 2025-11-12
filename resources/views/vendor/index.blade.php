@extends('layouts.main')

@section('title', 'Data Vendor')

@section('content')
  <div class="pagetitle">
    <h1>Data Vendor</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Vendor</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mt-3">
          <a href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary mb-3">+ Tambah</a>

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
              <th>Kode Vendor</th>
              <th>Nickname</th>
              <th>Vendor Name</th>
              <th>Alamat</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($vendors as $v)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $v->kode_vendor }}</td>
                <td>{{ $v->nickname }}</td>
                <td>{{ $v->vendor_name }}</td>
                <td>{{ $v->alamat }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('vendor.edit', $v->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('vendor.delete', $v->id) }}" method="POST">
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