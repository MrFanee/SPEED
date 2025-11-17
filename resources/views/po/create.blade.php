@extends('layouts.main')

@section('title', 'Tambah PO')

@section('content')
    <div class="pagetitle">
        <h1>Tambah PO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('po.index') }}">PO</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form PO</h5>

                <form action="{{ route('po.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="po_number" class="form-label">No. PO</label>
                        <input type="text" name="po_number" class="form-control" id="po_number"
                            value="{{ old('po_number') }}">
                        @error('po_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="vendor_id">Vendor</label>
                        <select name="vendor_id" id="vendor_id" class="form-select" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach ($vendor as $v)
                                <option value="{{ $v->id }}">{{ $v->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="part_id">Part</label>
                        <select name="part_id" id="part_id" class="form-select" required>
                            <option value="">-- Pilih Part --</option>
                            @foreach ($vendopartr as $p)
                                <option value="{{ $p->id }}">{{ $p->part_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="qty_po" class="form-label">Qty PO</label>
                        <input type="text" name="qty_po" class="form-control" id="qty_po" value="{{ old('qty_po') }}">
                        @error('qty_po') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="qty_outstanding" class="form-label">OS PO</label>
                        <input type="text" name="qty_outstanding" class="form-control" id="qty_outstanding"
                            value="{{ old('qty_outstanding') }}">
                        @error('qty_outstanding') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="{{ route('po.index') }}" class="btn btn-sm btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </section>
@endsection