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
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    @if(auth()->user()->role !== 'vendor')
                        {{-- <a href="{{ route('di.create') }}" class="btn btn-sm btn-primary">+ Tambah</a> --}}
                        <a href="{{ route('di.upload') }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-upload"></i> Upload CSV
                        </a>
                    @endif

                    <form action="{{ route('di.index') }}" method="GET" class="d-flex gap-2">
                        <select name="bulan" class="form-select form-select-sm" style="width: 120px"
                            onchange="this.form.submit()">
                            @foreach($bulanList as $bln)
                                <option value="{{ $bln }}" {{ $bln == $bulan ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->locale('id')->month($bln)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select name="tahun" class="form-select form-select-sm" style="width: 100px"
                            onchange="this.form.submit()">
                            @foreach ($tahunList as $th)
                                <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                    {{ $th }}
                                </option>
                            @endforeach
                        </select>
                    </form>
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

                <table class="table table-bordered table-striped small" id="diTable">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Part Name</th>
                            <th>PO</th>
                            <th>∑ Plan</th>
                            <th>∑ Delivery</th>
                            <th>Balance</th>
                            {{-- @if(auth()->user()->role !== 'vendor')
                                <th>Aksi</th>
                            @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($di as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->item_code }}</td>
                                <td>{{ $d->part_name }}</td>
                                <td>{{ $d->po_number }}</td>
                                <td>{{ $d->qty_plan }}</td>
                                <td>{{ $d->qty_delivery }}</td>
                                <td>{{ $d->balance }}</td>
                                {{-- @if(auth()->user()->role !== 'vendor')
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('di.edit', $d->id) }}" class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('di.delete', $d->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin mau hapus data ini?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                @endif --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.querySelector("#diTable");
            if (table) {
                new simpleDatatables.DataTable(table);
            }
        });
    </script>
@endsection