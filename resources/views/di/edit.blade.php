@extends('layouts.main')

@section('title', 'Edit DI')

@section('content')
    <div class="pagetitle">
        <h1>Edit DI</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('di.index') }}">DI</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Data DI</h5>

                <form action="{{ route('di.update', $di->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="po_id" class="form-label">PO</label>
                        <select class="form-select" id="po_id" name="po_id" required>
                            <option value="">-- Pilih PO --</option>
                            @foreach ($poList as $p)
                                <option value="{{ $p->id }}" {{ $di->po_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->po_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="qty_plan" class="form-label">∑ Plan</label>
                        <input type="text" class="form-control" id="qty_plan" name="qty_plan" value="{{ $di->qty_plan }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="qty_delivery" class="form-label">∑ Delivery</label>
                        <input type="text" class="form-control" id="qty_delivery" name="qty_delivery"
                            value="{{ $di->qty_delivery }}" required>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('di.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </section>
@endsection