@extends('layouts.main')
@section('title', 'Daily Stock Report')

@section('content')
<div class="pagetitle">
    <h1>Daily Report ({{ $tanggal }})</h1>
</div>

<form method="GET" class="mb-3">
    <input type="date" name="tanggal" value="{{ $tanggal }}">
    <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
</form>

<table class="table table-bordered small text-center">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>Item Code</th>
            <th>Part</th>
            <th>PO</th>
            <th>FG</th>
            <th>Std. 2HK</th>
            <th>Judgement</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
        <tr>
            <td>{{ $d->nickname }}</td>
            <td>{{ $d->item_code }}</td>
            <td>{{ $d->part_name }}</td>
            <td>{{ $d->qty_po }}</td>
            <td>{{ $d->fg }}</td>
            <td>{{ $d->std_stock }}</td>
            <td>{{ $d->judgement }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
