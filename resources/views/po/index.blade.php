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

                    @if(auth()->user()->role !== 'vendor')
                        <a href="{{ route('po.upload') }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-upload"></i> Upload CSV
                        </a>
                    @endif

                    <form action="{{ route('po.index') }}" method="GET" class="d-flex gap-2">

                        <select name="bulan" class="form-select form-select-sm" id="bulanSelect" style="width: 120px"
                            onchange="this.form.submit()">
                            @foreach($bulanList as $bln)
                                <option value="{{ $bln }}" {{ $bln == $bulan ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->locale('id')->month($bln)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select name="tahun" class="form-select form-select-sm" id="tahunSelect" style="width: 100px"
                            onchange="onTahunChange(this)">
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

                <table class="table table-bordered table-striped small" id="poTable">
                    <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Periode</th>
                            <th>No. PO</th>
                            <th>Purch. Group</th>
                            <th>Vendor</th>
                            <th>Item Code</th>
                            <th>Part Name</th>
                            <th>Qty PO</th>
                            <th>OS PO</th>
                            <th>Delv. Date</th>
                            {{-- <th>Status</th> --}}
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($po as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->period }}</td>
                                <td>{{ $p->po_number }}</td>
                                <td>{{ $p->purchase_group }}</td>
                                <td>{{ $p->vendor->vendor_name }}</td>
                                <td>{{ $p->part->item_code }}</td>
                                <td>{{ $p->part->part_name }}</td>
                                <td>{{ $p->qty_po }}</td>
                                <td>{{ $p->qty_outstanding }}</td>
                                <td>{{ $p->delivery_date }}</td>
                                {{-- <td>{{ $p->status }}</td> --}}
                                {{-- <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('po.edit', $p->id) }}" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('po.delete', $p->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin mau hapus data ini?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.querySelector("#poTable");
            if (table) {
                new simpleDatatables.DataTable(table);
            }
        });
    </script>

    <script>
        function onTahunChange(el) {
            const form = el.form;

            const bulanSelect = form.querySelector('select[name="bulan"]');
            if (bulanSelect) {
                bulanSelect.disabled = true;
            }

            form.submit();
        }
    </script>

@endsection