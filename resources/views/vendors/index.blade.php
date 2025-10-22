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
      <h5 class="card-title">Daftar Vendor</h5>

      <a href="{{ route('vendors.create') }}" class="btn btn-primary mb-3">+ Tambah Vendor</a>

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
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
            <td>{{ $v->nickname }}</td>
            <td>{{ $v->vendor_name }}</td>
            <td>{{ $v->alamat }}</td>
            <td>
              <a href="{{ route('vendors.edit', $v->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('vendors.destroy', $v->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus vendor ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  </div>
</section>
@endsection
