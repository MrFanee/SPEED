@extends('layouts.main')
@section('title', 'Yearly Stock Report')

@section('content')
<div class="pagetitle">
    <h1>Yearly Report ({{ $year }})</h1>
</div>

<form method="GET" class="mb-3">
    <input type="number" name="year" min="2020" max="{{ now()->year }}" value="{{ $year }}">
    <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
</form>

<table class="table table-bordered small text-center">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>OK</th>
            <th>NG</th>
            <th>NO PO</th>
            <th>Total</th>
            <th>% OK</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
        <tr>
            <td>{{ $d->nickname }}</td>
            <td>{{ $d->ok_count }}</td>
            <td>{{ $d->ng_count }}</td>
            <td>{{ $d->nopo_count }}</td>
            <td>{{ $d->total }}</td>
            <td>{{ round(($d->ok_count / $d->total) * 100, 1) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
