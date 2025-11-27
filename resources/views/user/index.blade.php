@extends('layouts.main')

@section('title', 'Data User')

@section('content')
  <div class="pagetitle">
    <h1>Data User</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">User</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mt-3">
          <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary mb-3">+ Tambah</a>
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

        <table class="table table-bordered table-striped small" id="userTable">
          <thead class="text-center">
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Role</th>
              <th>Vendor</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $u)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->role }}</td>
                <td>{{ $u->vendor->vendor_name ?? '-' }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('user.edit', $u->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('user.delete', $u->id) }}" method="POST">
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
      const table = document.querySelector("#userTable");
      if (table) {
        new simpleDatatables.DataTable(table);
      }
    });
  </script>
@endsection