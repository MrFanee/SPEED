@extends('layouts.main')

@section('title', 'Data DI')

@section('content')
    <div class="pagetitle">
        <h1>Data DI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">DI</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                    <a href="{{ route('di.create') }}" class="btn btn-sm btn-primary">+ Tambah</a>

                    <a href="{{ route('di.upload') }}" class="btn btn-sm btn-success">Upload CSV</a>
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
                            <th>Qty PO</th>
                            <th>∑ Plan</th>
                            <th>∑ Delivery</th>
                            <th>Balance</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($di as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->po->qty_po }}</td>
                                <td>{{ $d->qty_plan }}</td>
                                <td>{{ $d->qty_delivery }}</td>
                                <td>{{ $d->balance }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('di.edit', $d->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('di.delete', $d->id) }}" method="POST">
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