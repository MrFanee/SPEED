@extends('layouts.main')

@section('title', 'Daily Vendor Report')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Daily Report</h1>
        <form action="{{ route('report.vendor') }}" method="get" class="d-flex gap-2 align-items-center">
            <div class="input-group" style="width: 150px;">
                <input type="date" name="tanggal_pilih" class="form-control" value="{{ $tanggalPilih ?? date('Y-m-d') }}"
                    max="{{ date('Y-m-d') }}"
                    onchange="this.form.submit()">
            </div>
        </form>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body table-responsive text-center">
                <div class="alert mb-0 mt-2 fw-bold">
                    DAILY REPORT – 
                    {{ \Carbon\Carbon::parse($tanggalPilih)->locale('id')->translatedFormat('d F Y') }}
                </div>
                <table class="table table-bordered table-striped small mt-2 table" style="font-size: 12px;">
                    <thead class="text-center">
                        <tr>
                            <th>Vendor</th>
                            <th>Total Item</th>
                            <th>Stok NG</th>
                            <th>Stok OK</th>
                            <th>On Schedule</th>
                            <th>Material</th>
                            <th>Man</th>
                            <th>Machine</th>
                            <th>Method</th>
                            <th>Konsistensi Report</th>
                            <th>Akurasi Stok</th>
                            <th>Akurasi Schedule</th>
                            <th>% Material</th>
                            <th>% Man</th>
                            <th>% Machine</th>
                            <th>% Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $row)
                            <tr>
                                <td class="text-start">{{ $row['vendor'] }}</td>
                                <td>{{ $row['total_item'] }}</td>
                                <td>{{ $row['stok_ng'] }}</td>
                                <td>{{ $row['stok_ok'] }}</td>
                                <td>{{ $row['on_schedule'] }}</td>
                                <td>{{ $row['material'] }}</td>
                                <td>{{ $row['man'] }}</td>
                                <td>{{ $row['machine'] }}</td>
                                <td>{{ $row['method'] }}</td>
                                <td>{{ $row['konsistensi'] }}%</td>
                                <td>{{ $row['akurasi_stok'] }}%</td>
                                <td>{{ $row['akurasi_schedule'] }}%</td>
                                <td>{{ $row['persen_material'] }}%</td>
                                <td>{{ $row['persen_man'] }}%</td>
                                <td>{{ $row['persen_machine'] }}%</td>
                                <td>{{ $row['persen_method'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center text-muted">Tidak ada data untuk tanggal ini</td>
                            </tr>
                        @endforelse
                        <tr class="fw-bold table-warning">
                            <td class="text-center">Total / Rata-rata</td>
                            <td>{{ $summary['total_item'] }}</td>
                            <td>{{ $summary['stok_ng'] }}</td>
                            <td>{{ $summary['stok_ok'] }}</td>
                            <td>{{ $summary['on_schedule'] }}</td>
                            <td>{{ $summary['material'] }}</td>
                            <td>{{ $summary['man'] }}</td>
                            <td>{{ $summary['machine'] }}</td>
                            <td>{{ $summary['method'] }}</td>
                            <td>{{ $summary['konsistensi'] }}%</td>
                            <td>{{ $summary['akurasi_stok'] }}%</td>
                            <td>{{ $summary['akurasi_schedule'] }}%</td>
                            <td>{{ $summary['persen_material'] }}%</td>
                            <td>{{ $summary['persen_man'] }}%</td>
                            <td>{{ $summary['persen_machine'] }}%</td>
                            <td>{{ $summary['persen_method'] }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection