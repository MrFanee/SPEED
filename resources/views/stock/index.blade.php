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
                            <tr data-id="{{ $s->id }}" data-judgement="{{ $s->judgement }}">
                                <td>{{ $s->nickname }}</td>
                                <td>{{ $s->item_code }}</td>
                                <td>{{ $s->part_name }}</td>
                                <td>{{ $s->qty_po ?? '-'}}</td>
                                <td>{{ $s->qty_outstanding ?? '-'}}</td>
                                <td>{{ $s->qty_plan ?? '-'}}</td>
                                <td>{{ $s->qty_delivery ?? '-'}}</td>
                                <td>{{ $s->balance ?? '-'}}</td>
                                <td contenteditable="true" class="editable" data-field="rm">{{ $s->rm }}</td>
                                <td contenteditable="true" class="editable" data-field="wip">{{ $s->wip }}</td>
                                <td contenteditable="true" class="editable" data-field="fg">{{ $s->fg }}</td>
                                <td>{{ $s->std_stock ?? '-'}}</td>
                                <td>
                                    @if ($s->judgement == 'OK')
                                        <span class="badge bg-success">{{ $s->judgement }}</span>
                                    @elseif ($s->judgement == 'NG')
                                        <span class="badge bg-danger">{{ $s->judgement }}</span>
                                    @elseif ($s->judgement == 'NO PO')
                                        <span class="badge bg-warning text-dark">{{ $s->judgement }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $s->judgement }}</span>
                                    @endif
                                </td>
                                <td contenteditable="true" class="editable" data-field="kategori_problem">
                                    {{ $s->kategori_problem }}
                                </td>
                                <td contenteditable="true" class="editable" data-field="detail_problem">{{ $s->detail_problem }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editableCells = document.querySelectorAll('.editable');

            editableCells.forEach(cell => {
                // Trigger save saat tekan ENTER
                cell.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.blur(); // trigger blur (auto save)
                    }
                });

                // Trigger save saat keluar cell
                cell.addEventListener('blur', function () {
                    const row = this.closest('tr');
                    const id = row.dataset.id;
                    const field = this.dataset.field;
                    const newValue = this.textContent.trim();
                    const judgement = row.dataset.judgement;

                    const kategoriCell = row.querySelector('[data-field="kategori_problem"]');
                    const detailCell = row.querySelector('[data-field="detail_problem"]');

                    if (judgement === 'NG') {
                        const kategoriVal = kategoriCell.textContent.trim();
                        const detailVal = detailCell.textContent.trim();

                        if (!kategoriVal || !detailVal) {
                            alert('Harap isi Kategori Problem dan Detail Problem sebelum lanjut.');
                            this.focus();
                            return;
                        }
                    }

                    fetch(`/stock/update/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ field, value: newValue })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.style.backgroundColor = '#d4edda';
                            setTimeout(() => this.style.backgroundColor = '', 800);
                        } else {
                            alert(data.message || 'Gagal update data');
                        }
                    })
                    .catch(async (err) => {
                        console.error(err);
                        alert('Terjadi error: ' + err.message);
                    });
                });
            });
        });
    </script>

@endsection