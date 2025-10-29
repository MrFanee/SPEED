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
                <h5 class="card-title">2 Days Stock</h5>

                <div class="d-flex justify-content-between align-items-center mb-3">
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
                            <th>Stok 2HK</th>
                            <th>Judgement</th>
                            <th>Kategori Problem</th>
                            <th>Detail Problem</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            let table = $('#stockTable').DataTable({
                ajax: '{{ route('stock.data') }}',
                columns: [
                    { data: 'vendor.nickname', defaultContent: '-' },
                    { data: 'part.item_code', defaultContent: '-' },
                    { data: 'part.part_name', defaultContent: '-' },
                    { data: 'part.po[0].qty_po', defaultContent: '-' },
                    { data: 'part.po[0].qty_outstanding', defaultContent: '-' },
                    { data: 'part.di[0].qty_plan', defaultContent: '-' },
                    { data: 'part.di[0].qty_delivery', defaultContent: '-' },
                    { data: 'part.di[0].balance', defaultContent: '-' },
                    { data: 'rm' },
                    { data: 'wip' },
                    {
                        data: 'fg',
                        render: function (data, type, row) {
                            return `<input type="number" class="form-control form-control-sm inline-edit" data-id="${row.id}" data-field="fg" value="${data}">`;
                        }
                    },
                    { data: 'part.std_stock', defaultContent: '-' },
                    { data: 'judgement', defaultContent: '-' },
                    {
                        data: 'kategori_problem',
                        render: function (data, type, row) {
                            return `<input type="text" class="form-control form-control-sm inline-edit" data-id="${row.id}" data-field="kateori_problem" value="${data ?? ''}">`;
                        }
                    },
                    {
                        data: 'detail_problem',
                        render: function (data, type, row) {
                            return `<input type="text" class="form-control form-control-sm inline-edit" data-id="${row.id}" data-field="detail_problem" value="${data ?? ''}">`;
                        }
                    }
                ]
            });

            // Inline edit handler
            $('#stockTable').on('change', '.inline-edit', function () {
                let id = $(this).data('id');
                let field = $(this).data('field');
                let value = $(this).val();

                $.ajax({
                    url: `/stock/${id}/update`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        [field]: value
                    },
                    success: function (res) {
                        table.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON.error || 'Update gagal.');
                        table.ajax.reload(null, false);
                    }
                });
            });
        });
    </script>
@endsection