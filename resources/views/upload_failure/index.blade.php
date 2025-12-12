@extends('layouts.main')

@section('title', 'Upload Failure')

@section('content')
    <div class="pagetitle">
        <h1>Upload Failure</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Upload Failure</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3 small" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3 small" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table table-bordered table-striped small mt-3" id="uploadTable">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>Error</th>
                            <th>Status</th>
                            {{-- <th>Uploaded By</th> --}}
                            <th>Tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($failures as $f)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $f->module }}</td>
                                <td>{{ $f->error_message }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $f->status === 'success' ? 'success' : ($f->status === 'retry_failed' ? 'danger' : 'warning') }}">
                                        {{ $f->status }}
                                    </span>
                                </td>
                                {{-- <td>{{ $f->user->username ?? '-' }}</td> --}}
                                <td>{{ $f->created_at }}</td>
                                <td>
                                    <a href="{{ route('upload_failure.show', $f->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye-fill"></i> 
                                    </a>

                                    @if($f->status !== 'success')
                                        <form action="{{ route('upload_failure.retry', $f->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('upload_failure.delete', $f->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin mau hapus data ini?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                        @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data gagal upload.</td>
                                </tr>
                                </tr>
                            @endforelse
                    </tbody>
                </table>

                {{ $failures->links() }}
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.querySelector("#uploadTable");
            if (table) {
                new simpleDatatables.DataTable(table);
            }
        });
    </script>
@endsection