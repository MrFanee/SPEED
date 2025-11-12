@extends('layouts.main')
@section('title', 'Monthly Stock Report')

@section('content')
<div class="pagetitle">
    <h1>Monthly Report ({{ $month }})</h1>
</div>

<form method="GET" class="mb-3">
    <input type="month" name="month" value="{{ $month }}">
    <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
</form>

<table class="table table-bordered small text-center">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>Avg FG</th>
            <th>Avg Std. 2HK</th>
            <th>% NG</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
        <tr>
            <td>{{ $d->nickname }}</td>
            <td>{{ number_format($d->avg_fg, 1) }}</td>
            <td>{{ number_format($d->avg_std, 1) }}</td>
            <td>{{ round(($d->ng_count / $d->total) * 100, 1) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
