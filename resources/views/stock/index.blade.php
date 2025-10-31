@extends('layouts.main')

@section('title', '2 Days Stock')

@section('content')
    <div class="pagetitle">
        <h1>2 Days Stock</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">2 Days Stock</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    {{-- <a href="{{ route('po.create') }}" class="btn btn-primary">+ Tambah</a> --}}

                    <a href="{{ route('stock.upload') }}" class="btn btn-success">Upload CSV</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Item Code</th>
                            <th>Part</th>
                            <th>PO</th>
                            <th>OS PO</th>
                            <th>∑ Plan</th>
                            <th>∑ Delivery</th>
                            <th>Balance</th>
                            <th>RM</th>
                            <th>WIP</th>
                            <th>FG</th>
                            <th>Std 2HK</th>
                            <th>Judgement</th>
                            <th>Kategori Problem</th>
                            <th>Detail Problem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stock as $s)
                            <tr>
                                <td>{{ $s->vendor->nickname }}</td>
                                <td>{{ $s->part->item_code }}</td>
                                <td>{{ $s->part->part_name }}</td>
                                <td>{{ $s->part->po->qty_po ?? '-'}}</td>
                                <td>{{ $s->part->po->qty_outstanding ?? '-'}}</td>
                                <td>{{ $s->part->di->qty_plan ?? '-'}}</td>
                                <td>{{ $s->part->di->qty_delivery ?? '-'}}</td>
                                <td>{{ $s->part->di->balance ?? '-'}}</td>
                                <td>{{ $s->rm }}</td>
                                <td>{{ $s->wip }}</td>
                                <td>{{ $s->fg }}</td>
                                <td>{{ $s->part->master_2hk->std_stock ?? '-'}}</td>
                                <td>{{ $s->judgement }}</td>
                                <td>{{ $s->kategori_problem }}</td>
                                <td>{{ $s->detail_problem }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection