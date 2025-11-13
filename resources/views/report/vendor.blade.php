@extends('layouts.main')

@section('title', 'Daily Vendor Report')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Daily Report</h1>
        <form action="{{ route('report.vendor') }}" method="get" class="d-flex gap-2 align-items-center">
            <select name="tanggal" class="form-select w-auto" onchange="this.form.submit()">
                @foreach ($tanggalList as $tgl)
                    <option value="{{ $tgl }}" {{ $tgl == $tanggal ? 'selected' : '' }}>
                        {{ str_pad($tgl, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endforeach
            </select>

            <select name="bulan" class="form-select w-auto" onchange="this.form.submit()">
                @foreach ($bulanList as $bln)
                    <option value="{{ $bln }}" {{ $bln == $bulan ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($bln)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="form-select w-auto" onchange="this.form.submit()">
                @foreach ($tahunList as $th)
                    <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>
                        {{ $th }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped small mt-3">
                    <thead>
                        <tr>
                            {{-- <th>Tanggal</th> --}}
                            <th>Vendor</th>
                            <th>Total Item</th>
                            <th>Stok NG</th>
                            <th>Stok OK</th>
                            <th>On Schedule</th>
                            <th>Material</th>
                            <th>Man</th>
                            <th>Machine</th>
                            <th>Method</th>
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
                                {{-- <td>{{ $row['tanggal']}}</td> --}}
                                <td>{{ $row['vendor'] }}</td>
                                <td>{{ $row['total_item'] }}</td>
                                <td>{{ $row['stok_ng'] }}</td>
                                <td>{{ $row['stok_ok'] }}</td>
                                <td>{{ $row['on_schedule'] }}</td>
                                <td>{{ $row['material'] }}</td>
                                <td>{{ $row['man'] }}</td>
                                <td>{{ $row['machine'] }}</td>
                                <td>{{ $row['method'] }}</td>
                                <td>{{ $row['akurasi_stok'] }}%</td>
                                <td>{{ $row['akurasi_schedule'] }}%</td>
                                <td>{{ $row['persen_material'] }}%</td>
                                <td>{{ $row['persen_man'] }}%</td>
                                <td>{{ $row['persen_machine'] }}%</td>
                                <td>{{ $row['persen_method'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">Tidak ada data untuk tanggal ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection