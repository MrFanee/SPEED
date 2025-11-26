@extends('layouts.main')

@section('title', 'Tambah DI')

@section('content')
    <div class="pagetitle">
        <h1>Tambah DI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('di.index') }}">DI</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form DI</h5>

                <form action="{{ route('di.store') }}" method="POST" class="small">
                    @csrf

                    <div class="mb-3">
                        <label for="po_id">No. PO</label>
                        @error('po_id')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror
                        <select name="po_id" id="po_id" class="form-select">
                            <option value="">-- Pilih PO --</option>
                            @foreach ($po as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="qty_plan" class="form-label">∑ Plan</label>
                        @error('qty_plan')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror
                        <input type="text" name="qty_plan" class="form-control" id="qty_plan" value="{{ old('qty_plan') }}">
                    </div>

                    <div class="mb-3">
                        <label for="qty_delivery" class="form-label">∑ Delivery</label>
                        @error('qty_delivery')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror
                        <input type="text" name="qty_delivery" class="form-control" id="qty_delivery"
                            value="{{ old('qty_delivery') }}">
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="{{ route('di.index') }}" class="btn btn-sm btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </section>
@endsection