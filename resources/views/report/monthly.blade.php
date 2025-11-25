@extends('layouts.main')

@section('title', 'Monthly Vendor Report')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Monthly Report</h1>
        <form action="{{ route('report.monthly') }}" method="get" class="d-flex gap-2 align-items-center">
            @if(auth()->user()->role !== 'vendor')
            <select name="vendor" class="form-select w-auto" onchange="this.form.submit()">
                @foreach ($vendorList as $v)
                    <option value="{{ $v }}" {{ $v == $vendor ? 'selected' : '' }}>
                        {{ $v }}
                    </option>
                @endforeach
            </select>
            @endif

            <select name="bulan" class="form-select w-auto" onchange="this.form.submit()">
                @foreach ($bulanList as $bln)
                    <option value="{{ $bln }}" {{ $bln == $bulan ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->locale('id')->month($bln)->translatedFormat('F') }}
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
            <div class="card-body table-responsive text-center">
                <div class="card mb-3 mt-3">
                    <div class="card-body">
                        <h6 class="text-center mt-2 mb-2 fw-bold">
                            ACHIEVEMENT 2 DAYS STOCK – {{ $vendorName }} periode
                            {{ \Carbon\Carbon::create()->locale('id')->month($bulan)->year($tahun)->translatedFormat('F Y') }}
                        </h6>
                        <canvas id="monthlyChart" height="100"></canvas>
                    </div>
                </div>

                <table class="table table-bordered table-striped small" style="font-size: 12px;">
                    <thead class="text-center">
                        <tr>
                            <th>Tanggal</th>
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
                            {{-- <th>% Material</th>
                            <th>% Man</th>
                            <th>% Machine</th>
                            <th>% Method</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $row)
                            <tr>
                                <td class="text-start">
                                    {{ \Carbon\Carbon::parse($row['tanggal'])->locale('id')->translatedFormat('d-M') }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center text-muted">Tidak ada data untuk vendor ini</td>
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
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const labels = @json(array_column($report, 'tanggal'));
            const akurasiStok = @json(array_column($report, 'akurasi_stok'));
            const akurasiSchedule = @json(array_column($report, 'akurasi_schedule'));
            const konsistensi = @json(array_column($report, 'konsistensi'));
            const summary = @json($summary);

            const formattedLabels = labels.map(t => {
                const d = new Date(t);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            });

            formattedLabels.push('Rata-rata');

            akurasiStok.push(summary.akurasi_stok);
            akurasiSchedule.push(summary.akurasi_schedule);
            konsistensi.push(summary.konsistensi);

            const ctx = document.getElementById('monthlyChart').getContext('2d');
            const chartLegendMargin = {
                id: 'chartLegendMargin',
                beforeInit: function (chart) {
                    const originalFit = chart.legend.fit;
                    chart.legend.fit = function fit() {
                        originalFit.bind(chart.legend)();
                        this.height += 20;
                    }
                }
            };

            new Chart(ctx, {
                type: 'bar',
                plugins: [ChartDataLabels, chartLegendMargin],
                data: {
                    labels: formattedLabels,
                    datasets: [
                        {
                            label: 'Akurasi Stok (%)',
                            data: akurasiStok,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Akurasi Schedule (%)',
                            data: akurasiSchedule,
                            backgroundColor: 'rgba(255, 159, 64, 0.7)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Konsistensi Report (%)',
                            type: 'line',
                            data: konsistensi,
                            borderColor: 'rgba(75, 192, 192,1)',
                            borderWidth: 2,
                            tension: 0.2,
                            fill: false,
                            yAxisID: 'y',
                            datalabels: {
                                display: false
                            }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: value => value + '%',
                            font: { weight: 'bold', size: 9 }
                        }
                    }
                }
            });
        });
    </script>

@endsection