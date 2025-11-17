@extends('layouts.main')

@section('title', 'Edit PO')

@section('content')
    <div class="pagetitle">
        <h1>Edit PO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('po.index') }}">PO</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Data PO</h5>

                <form action="{{ route('po.update', $po->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="po_number" class="form-label">No. PO</label>
                        <input type="text" class="form-control" id="po_number" name="po_number" value="{{ $po->po_number }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="vendor_id" class="form-label">Vendor</label>
                        <select class="form-select" id="vendor_id" name="vendor_id" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach ($vendorList as $v)
                                <option value="{{ $v->id }}" {{ $di->vendor_id == $v->id ? 'selected' : '' }}>
                                    {{ $v->vendor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="part_id" class="form-label">Part</label>
                        <select class="form-select" id="part_id" name="part_id" required>
                            <option value="">-- Pilih Part --</option>
                            @foreach ($partList as $p)
                                <option value="{{ $p->id }}" {{ $di->part_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->part_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="qty_po" class="form-label">Qty PO</label>
                        <input type="text" class="form-control" id="qty_po" name="qty_po"
                            value="{{ $po->qty_po }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="qty_outstanding" class="form-label">OS PO</label>
                        <input type="text" class="form-control" id="qty_outstanding" name="qty_outstanding"
                            value="{{ $po->qty_outstanding }}" required>
                    </div>

                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                    <a href="{{ route('po.index') }}" class="btn btn-sm btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </section>
@endsection