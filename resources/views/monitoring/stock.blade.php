@extends('layouts.dashboard')
@section('title', 'Dashboard Stock Monitoring')

@section('content')
    <div class="container-fluid mt-3">
        <div class="row mb-3">

            {{-- KIRI : PIE --}}
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="row text-center g-2" style="overflow-x:hidden;">
                    @foreach($pieData as $vendor => $data)
                        <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4 col-6">
                            <div class="fw-bold small text-truncate" style="color: #213555;" title="{{ $vendor }}">
                                {{ $vendor }}
                            </div>
                            <canvas id="chart-{{ $loop->index }}" height="110"></canvas>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KANAN : BAR --}}
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-bold text-center mb-2" style="color:#213555;">
                            DI Delay
                        </div>
                        <div style="flex:1; min-height:180px;">
                            <canvas id="barVendorChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="table-responsive table-wrapper" style="max-height: calc(100vh - 400px); overflow-y: auto;">
            <table class="table table-bordered table-striped table-hover" style="font-size: 10px;">
                <thead class="text-center sticky-top bg-light">
                    <tr>
                        <th>Vendor</th>
                        <th>Item Code</th>
                        <th>Part Name</th>
                        <th>RM</th>
                        <th>WIP</th>
                        <th>FG</th>
                        <th>Std 2HK</th>
                        <th>Judgement</th>
                        <th>Delay</th>
                        <th>Manifest</th>
                        <th>Kategori Problem</th>
                        <th>Detail Problem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stock as $row)
                        <tr>
                            <td class="text-center">{{ $row->nickname }}</td>
                            <td>{{ $row->item_code }}</td>
                            <td>{{ $row->part_name }}</td>
                            <td class="text-center">{{ $row->rm }}</td>
                            <td class="text-center">{{ $row->wip }}</td>
                            <td class="text-center">{{ $row->fg }}</td>
                            <td class="text-center">{{ $row->std_stock }}</td>
                            <td
                                class="fw-bold text-center text-white {{ $row->judgement == 'NG' ? 'bg-danger' : 'bg-success' }}">
                                {{ $row->judgement }}
                            </td>
                            <td class="fw-bold text-center {{ $row->qty_delay > 0 ? 'bg-danger text-white' : 'text-dark' }}">
                                {{ $row->qty_delay }}
                            </td>
                            <td class="text-center">{{ $row->qty_manifest }}</td>
                            <td>{{ $row->kategori_problem }}</td>
                            <td>{{ $row->detail_problem }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

    <script>
        @foreach($pieData as $vendor => $data)
            const ctx{{ $loop->index }} = document.getElementById('chart-{{ $loop->index }}');

            new Chart(ctx{{ $loop->index }}, {
                type: 'pie',
                data: {
                    labels: ['OK', 'NG'],
                    datasets: [{
                        data: [{{ $data['OK'] }}, {{ $data['NG'] }}],
                        backgroundColor: ['#5CB338', '#FFC145'],
                        borderColor: '#ffffff',
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function (value, context) {
                                return value;
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // plugin untuk value
            });
        @endforeach
    </script>

    <script>
            const barLabels = [
            @foreach($barData as $vendor => $data)
                "{{ $vendor }}",
            @endforeach
            ];

        const delayData = [
            @foreach($barData as $vendor => $data)
                {{ $data['delay'] }},
            @endforeach
            ];

        const normalData = [
            @foreach($barData as $vendor => $data)
                {{ $data['normal'] }},
            @endforeach
            ];

        const barCtx = document.getElementById('barVendorChart');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [
                    {
                        label: 'Delay',
                        data: delayData,
                        backgroundColor: '#FB4141'
                    },
                    {
                        label: 'Closed',
                        data: normalData,
                        backgroundColor: '#ECE852'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },

                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: 'grey',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function (value) {
                            return value > 0 ? value : '';
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 60,
                            minRotation: 60,
                            font: { size: 10 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

    </script>

@endsection