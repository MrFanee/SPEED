@extends('layouts.main')

@section('title', 'Data PO')

@section('content')
    <div class="pagetitle">
        <h1>Data PO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">PO</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    {{-- <a href="{{ route('po.create') }}" class="btn btn-primary">+ Tambah</a> --}}

                    <a href="{{ route('po.upload') }}" class="btn btn-sm btn-success">Upload CSV</a>
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
                            <th>No. PO</th>
                            <th>Vendor</th>
                            <th>Item Code</th>
                            <th>Part Name</th>
                            <th>Qty PO</th>
                            <th>OS PO</th>
                            <th>Status</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($po as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->po_number }}</td>
                                <td>{{ $p->vendor->vendor_name }}</td>
                                <td>{{ $p->part->item_code }}</td>
                                <td>{{ $p->part->part_name }}</td>
                                <td>{{ $p->qty_po }}</td>
                                <td>{{ $p->qty_outstanding }}</td>
                                <td>{{ $p->status }}</td>
                                {{-- <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('po.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('po.delete', $p->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
@endsection