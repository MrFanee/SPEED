@extends('layouts.main')
@section('searchbar')
    <div class="search-bar mb-3">
        <form id="searchForm" class="search-form d-flex align-items-center" method="GET"
            action="{{ route('stock.index') }}">
            <div class="position-relative w-100">
                <input type="text" id="searchInput" name="query" placeholder="Search..." class="form-control"
                    value="{{ request('query') }}">
                <span id="clearSearch" class="position-absolute top-50 end-0 translate-middle-y me-3"
                    style="cursor:pointer;">
                    <i class="bi bi-x-lg"></i>
                </span>
            </div>
        </form>
    </div>
@endsection

@section('title', '2 Days Stock')


@section('content')
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>2 Days Stock</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">2 Days Stock</li>
                    </ol>
                </nav>
            </div>

            <form action="{{ route('stock.index') }}" method="get" class="d-flex gap-2 align-items-center">
                <div class="input-group" style="width: 150px;">
                    <input type="date" name="tanggal" class="form-control"
                        value="{{ request('tanggal') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                        onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    @php
        $isToday = ($tanggal == date('Y-m-d'));
    @endphp

    <section class="section">
        <div class="card">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    {{-- @if ($isToday) --}}
                        @if(auth()->user()->role !== 'vendor')
                            <form action="{{ route('stock.create') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Tambah +
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('stock.upload') }}" class="btn btn-success btn-sm">Upload CSV</a>
                    {{-- @endif --}}
                </div>

                <div style="max-height: 350px; overflow-y: auto; position: relative;">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show small d-inline-block float-end"
                            role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-bordered table-striped small table-responsive"
                        style="font-size: 9px; position: relative;">
                        <thead class="text-center"
                            style="position: sticky; top: 0; z-index: 1000 !important; background-color: white;">
                            <tr>
                                <th>Vendor</th>
                                <th>Item Code</th>
                                <th>Part</th>
                                <th>PO</th>
                                <th>OS PO</th>
                                <th>∑ Plan</th>
                                <th>∑ Delv.</th>
                                <th>Balance</th>
                                <th>RM</th>
                                <th>WIP</th>
                                <th>FG</th>
                                <th>Std. 2HK</th>
                                <th>Judge.</th>
                                <th>Kategori Problem</th>
                                <th>Detail Problem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stock as $s)
                                <tr data-id="{{ $s->id }}" data-judgement="{{ $s->judgement }}">
                                    <td>{{ $s->nickname }}</td>
                                    <td>{{ $s->item_code }}</td>
                                    <td class="text-start">{{ $s->part_name }}</td>
                                    <td>{{ $s->qty_po ?? '-'}}</td>
                                    <td>{{ $s->qty_outstanding ?? '-'}}</td>
                                    <td>{{ $s->qty_plan ?? '-'}}</td>
                                    <td>{{ $s->qty_delivery ?? '-'}}</td>
                                    <td>{{ $s->balance ?? '-'}}</td>
                                    <td @if ($isToday) contenteditable="true" class="editable" @endif data-field="rm">
                                        {{ $s->rm }}
                                    </td>
                                    <td @if ($isToday) contenteditable="true" class="editable" @endif data-field="wip">
                                        {{ $s->wip }}
                                    </td>
                                    <td @if ($isToday) contenteditable="true" class="editable" @endif data-field="fg">
                                        {{ $s->fg }}
                                    </td>
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
                                    <td data-field="kategori_problem" class="@if ($isToday) editable @endif">
                                        <select class="kategori-problem" data-id="{{ $s->id }}" @if (!$isToday) disabled @endif>
                                            <option value="">- Pilih -</option>
                                            <option value="Man" {{ $s->kategori_problem == 'Man' ? 'selected' : '' }}>Man</option>
                                            <option value="Material" {{ $s->kategori_problem == 'Material' ? 'selected' : '' }}>
                                                Material</option>
                                            <option value="Machine" {{ $s->kategori_problem == 'Machine' ? 'selected' : '' }}>
                                                Machine
                                            </option>
                                            <option value="Method" {{ $s->kategori_problem == 'Method' ? 'selected' : '' }}>Method
                                            </option>
                                        </select>
                                    </td>
                                    <td class="text-start @if ($isToday) editable @endif" @if ($isToday) contenteditable="true"
                                    @endif data-field="detail_problem">
                                        {{ $s->detail_problem }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted">Tidak ada data untuk tanggal ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    @if ($isToday)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editableCells = document.querySelectorAll('.editable');

                // --- recalculate judgement ---
                function recalcJudgement(row) {
                    const poCell = row.querySelector('[data-field="qty_po"]');
                    const fgCell = row.querySelector('[data-field="fg"]');
                    const stdCell = row.querySelector('[data-field="std_stock"]');

                    let jumlahPO = parseFloat(poCell?.textContent.trim()) || 0;
                    let fg = parseFloat(fgCell?.textContent.trim()) || 0;
                    let stdStock = parseFloat(stdCell?.textContent.trim()) || 0;

                    let judgement = "-";
                    if (jumlahPO === 0) {
                        judgement = "NO PO";
                    } else if (jumlahPO > 0 && fg >= stdStock) {
                        judgement = "OK";
                    } else if (jumlahPO > 0 && fg < stdStock) {
                        judgement = "NG";
                    }

                    row.dataset.judgement = judgement;
                    return judgement;
                }

                // --- update badge ---
                function updateJudgementBadge(row, judgement) {
                    const badgeCell = row.querySelector('td:nth-child(13)');
                    const oldBadge = badgeCell.querySelector('.badge');

                    let newBadge = document.createElement('span');
                    newBadge.classList.add('badge');

                    if (judgement === 'OK') newBadge.classList.add('bg-success');
                    else if (judgement === 'NG') newBadge.classList.add('bg-danger');
                    else if (judgement === 'NO PO') newBadge.classList.add('bg-warning', 'text-dark');
                    else newBadge.classList.add('bg-secondary');

                    newBadge.textContent = judgement;

                    if (oldBadge) oldBadge.replaceWith(newBadge);
                    else badgeCell.appendChild(newBadge);
                }

                // --- highlight problem fields ---
                function highlightProblemFields(row) {
                    const kategoriCell = row.querySelector('[data-field="kategori_problem"]');
                    const detailCell = row.querySelector('[data-field="detail_problem"]');
                    const judgement = row.dataset.judgement;

                    if (kategoriCell) kategoriCell.style.border = '';
                    if (detailCell) detailCell.style.border = '';

                    if (judgement === 'NG') {
                        const kategoriVal = kategoriCell?.textContent.trim();
                        const detailVal = detailCell?.textContent.trim();

                        if (!kategoriVal || !detailVal) {
                            if (kategoriCell) kategoriCell.style.border = '1px solid #dc3545';
                            if (detailCell) detailCell.style.border = '1px solid #dc3545';
                        }
                    }
                }

                // --- reset problem fields jika judgement bukan NG ---
                function resetProblemFields(row) {
                    const kategoriCell = row.querySelector('[data-field="kategori_problem"]');
                    const detailCell = row.querySelector('[data-field="detail_problem"]');
                    const kategoriSelect = kategoriCell?.querySelector('select');
                    const id = row.dataset.id;

                    if (!id) return;

                    if (kategoriSelect) {
                        kategoriSelect.value = '';
                    } else if (kategoriCell) {
                        kategoriCell.textContent = '';
                    }

                    if (detailCell) detailCell.textContent = '';

                    const updates = [
                        { field: 'kategori_problem', value: '' },
                        { field: 'detail_problem', value: '' }
                    ];

                    updates.forEach(u => {
                        fetch(`/stock/update/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(u)
                        });
                    });
                }

                // --- cek baris NG dengan problem ga lengkap ---
                function isTableLocked() {
                    const rows = document.querySelectorAll('tr[data-judgement]');
                    for (const row of rows) {
                        const j = row.dataset.judgement;
                        if (j === 'NG') {
                            const kategoriVal = row.querySelector('[data-field="kategori_problem"]')?.textContent.trim();
                            const detailVal = row.querySelector('[data-field="detail_problem"]')?.textContent.trim();
                            if (!kategoriVal || !detailVal) return row;
                        }
                    }
                    return null;
                }

                // --- Event listeners ---
                editableCells.forEach(cell => {

                    // focus ke cell lain saat tabel terkunci
                    cell.addEventListener('focus', function (e) {
                        const lockedRow = isTableLocked();
                        const field = this.dataset.field;
                        const row = this.closest('tr');

                        if (lockedRow && (row !== lockedRow || !['kategori_problem', 'detail_problem'].includes(field))) {
                            e.preventDefault();
                            this.blur();

                            cell.style.backgroundColor = '#f8d7da';
                            setTimeout(() => cell.style.backgroundColor = '', 400);
                            return;
                        }
                    });

                    // Enter key
                    cell.addEventListener('keypress', function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const row = this.closest('tr');
                            const field = this.dataset.field;

                            if (['fg'].includes(field)) {
                                const newJudgement = recalcJudgement(row);
                                updateJudgementBadge(row, newJudgement);

                                if (newJudgement !== 'NG') resetProblemFields(row);
                            }

                            highlightProblemFields(row);
                            this.blur();
                        }
                    });

                    // Arrow keys
                    cell.addEventListener('keydown', function (e) {
                        if (['ArrowDown', 'ArrowUp', 'ArrowRight', 'ArrowLeft'].includes(e.key)) {
                            const lockedRow = isTableLocked();
                            const field = this.dataset.field;
                            const row = this.closest('tr');

                            // kalau tabel terkunci dan bukan kolom problem di baris NG
                            if (lockedRow && (row !== lockedRow || !['kategori_problem', 'detail_problem'].includes(field))) {
                                e.preventDefault();
                                return;
                            }

                            let nextCell;
                            if (e.key === 'ArrowDown') {
                                nextCell = row.nextElementSibling?.cells[this.cellIndex];
                            } else if (e.key === 'ArrowUp') {
                                nextCell = row.previousElementSibling?.cells[this.cellIndex];
                            } else if (e.key === 'ArrowRight') {
                                nextCell = this.nextElementSibling;
                            } else if (e.key === 'ArrowLeft') {
                                nextCell = this.previousElementSibling;
                            }

                            if (nextCell && nextCell.classList.contains('editable')) {
                                e.preventDefault();
                                nextCell.focus();
                            }
                        }
                    });

                    // Blur event (simpan)
                    cell.addEventListener('blur', function () {
                        const row = this.closest('tr');
                        const id = row.dataset.id;
                        const field = this.dataset.field;
                        const newValue = this.textContent.trim();

                        highlightProblemFields(row);

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

                                    if (data.judgement) {
                                        updateJudgementBadge(row, data.judgement);
                                        row.dataset.judgement = data.judgement;
                                        highlightProblemFields(row);

                                        if (data.judgement !== 'NG') resetProblemFields(row);
                                    }
                                }
                            });
                    });
                });

                // Event untuk dropdown kategori
                document.addEventListener('change', function (e) {
                    if (e.target.matches('select.kategori-problem')) {
                        const row = e.target.closest('tr');
                        const id = e.target.dataset.id;
                        const value = e.target.value;

                        highlightProblemFields(row);

                        fetch(`/stock/update/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ field: 'kategori_problem', value })
                        });
                    }
                });
            });
        </script>
    @endif

    <script>
        const input = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const form = document.getElementById('searchForm');

        let timer;
        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 400);
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            form.submit();
        });
    </script>
@endsection