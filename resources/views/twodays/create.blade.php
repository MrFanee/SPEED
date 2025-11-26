@extends('layouts.main')

@section('title', 'Tambah Standar Stok')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Standar Stok</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('twodays.index') }}">Standar Stok</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Standar Stok</h5>

                <form action="{{ route('twodays.store') }}" method="POST" class="small">
                    @csrf

                    <div class="mb-3">
                        <label for="part_id">Nama Part</label>
                        
                        @error('part_id')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror

                        <select name="part_id" id="part_id" class="form-select">
                            <option value="">-- Pilih Part --</option>
                            @foreach ($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->part_name }} ({{ $part->item_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="std_stock" class="form-label">Standar Stok</label>
                        
                        @error('std_stock')
                            <div class="text-danger mb-1">{{ $message }}</div>
                        @enderror
                        
                        <input type="text" name="std_stock" class="form-control" id="std_stock" value="{{ old('std_stock') }}">
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="{{ route('twodays.index') }}" class="btn btn-sm btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </section>
@endsection