@extends('layouts.dashboard')
@section('title', 'Dashboard Stock Monitoring')

@section('content')
    <div class="container-fluid mt-2">
        <div class="row mb-2">

            {{-- PIE CHART --}}
            <div class="col-lg-6 col-md-12 mb-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header py-1" style="background: linear-gradient(90deg, #4635B1 0%, #B771E5 100%);">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bold" style="font-size: 12px;">STOCK SUMMARY</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center me-1">
                                    <div class="rounded-circle me-1" style="background: #00DFA2; width: 8px; height: 8px;">
                                    </div>
                                    <span class="text-white fw-bold" style="font-size: 12px;">OK</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle me-1" style="background: #FF5FCF; width: 8px; height: 8px;">
                                    </div>
                                    <span class="text-white fw-bold" style="font-size: 12px;">NG</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <div class="row g-1 text-center">
                            @foreach($pieData as $vendor => $data)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-4 mb-0">
                                    <div class="fw-bold text-truncate mb-0" style="color: #2c3e50; font-size: 9px;"
                                        title="{{ $vendor }}">
                                        {{ $vendor }}
                                    </div>
                                    <div class="position-relative" style="height: 60px;">
                                        <canvas id="chart-{{ $loop->index }}" height="60"></canvas>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- BAR CHART --}}
            <div class="col-lg-6 col-md-12 mb-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header py-1" style="background: linear-gradient(90deg, #FF3EA5 0%, #FF7ED4 100%);">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="text-white fw-bold" style="font-size: 12px;">DI DELAY SUMMARY</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center me-2">
                                    <div class="rounded-circle me-1" style="width:8px;height:8px;background:#ff3b3b;"></div>
                                    <span class="text-white fw-bold" style="font-size:12px;">Delay</span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle me-1" style="width:8px;height:8px;background:#c9e933;"></div>
                                    <span class="text-white fw-bold" style="font-size:12px;">Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-1">
                        <div style="height: 250px;">
                            <canvas id="barVendorChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- TABEL --}}
        <div class="row">
            <div class="col-lg-10 col-12">
                <div class="card shadow-sm border-0" style="height: calc(100vh - 430px);">
                    <div class="card-header py-1 d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(90deg, #2D46B9 0%, #9EDDFF 100%);">

                        <h6 class="mb-0 text-white fw-bold" style="font-size: 12px;">
                            DETAIL STOCK TIDAK MENCAPAI 2HK
                        </h6>

                        <form method="GET" action="" class="d-flex gap-1">
                            <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                            <input type="hidden" name="query" value="{{ request('query') }}">

                            <select name="vendor" class="form-select form-select-sm fw-bold"
                                style="font-size:12px; width:120px; color: #2D46B9;" onchange="this.form.submit()">
                                <option value="">ALL VENDOR</option>
                                @foreach($vendorList as $v)
                                    <option value="{{ $v->nickname }}" {{ request('vendor') == $v->nickname ? 'selected' : '' }}>
                                        {{ $v->nickname }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="card-body p-0" style="height: calc(100% - 38px);">
                        <div class="table-responsive h-100" id="autoScrollTable" style="overflow-y: auto;">
                            <table class="table table-sm table-hover mb-0" style="font-size: 11px;">
                                <thead class="sticky-top text-center">
                                    <tr>
                                        <th class="text-center p-1" style="width: 5%;">Vendor</th>
                                        <th class="p-1" style="width: 10%;">Item Code</th>
                                        <th class="p-1" style="width: 20%;">Part Name</th>
                                        <th class="text-center p-1" style="width: 4%;">RM</th>
                                        <th class="text-center p-1" style="width: 4%;">WIP</th>
                                        <th class="text-center p-1" style="width: 4%;">FG</th>
                                        <th class="text-center p-1" style="width: 5%;">Std 2HK</th>
                                        <th class="text-center p-1" style="width: 5%;">Judgement</th>
                                        <th class="text-center p-1" style="width: 4%;">Delay</th>
                                        <th class="text-center p-1" style="width: 6%;">Manifest</th>
                                        <th class="p-1" style="width: 10%;">Kategori Problem</th>
                                        <th class="p-1" style="width: 15%;">Detail Problem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stock as $row)
                                        <tr class="{{ $row->qty_delay > 0 ? 'bg-warning bg-opacity-25' : '' }}">
                                            <td class="text-center p-1 fw-bold" style="background-color: #f8f9fa;">
                                                {{ $row->nickname }}
                                            </td>
                                            <td class="p-1">
                                                <span class="badge bg-info text-dark">{{ $row->item_code }}</span>
                                            </td>
                                            <td class="p-1 text-truncate" style="max-width: 200px;"
                                                title="{{ $row->part_name }}">
                                                {{ $row->part_name }}
                                            </td>
                                            <td class="text-center p-1">{{ $row->rm }}</td>
                                            <td class="text-center p-1">{{ $row->wip }}</td>
                                            <td class="text-center p-1 fw-bold" style="color: #2c3e50;">{{ $row->fg }}</td>
                                            <td class="text-center p-1">{{ $row->std_stock }}</td>
                                            <td class="fw-bold text-center p-1 text-white"
                                                style="background-color: {{ $row->judgement == 'NG' ? '#FF5FCF' : '#00DFA2' }};">
                                                {{ $row->judgement }}
                                            </td>
                                            <td class="fw-bold text-center p-1"
                                                style="
                                                                                        background-color: {{ $row->qty_delay > 0 ? '#FF5FCF' : 'transparent' }};
                                                                                        color: {{ $row->qty_delay > 0 ? 'white' : '#00DFA2' }};">
                                                {{ $row->qty_delay }}
                                            </td>
                                            <td class="text-center p-1">{{ $row->qty_manifest }}</td>
                                            <td class="p-1 text-center">{{ $row->kategori_problem }}</td>
                                            <td class="p-1 text-center" style="max-width: 150px;">{{ $row->detail_problem }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LAST UPDATE --}}
            <div class="col-lg-2 col-12">
                <div class="card shadow-sm border-0" style="height: calc(100vh - 430px);">

                    <div class="card-header py-1 d-flex justify-content-center align-items-center"
                        style="background: linear-gradient(90deg, #2D46B9 0%, #9EDDFF 100%);">
                        <h6 class="mb-0 text-white fw-bold" style="font-size: 12px;">
                            LAST UPDATE DATA
                        </h6>
                    </div>

                    <div class="card-body p-2" id="autoScrollUpdate" style="font-size:12px; overflow-y:auto;">

                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-1">
                                <span class="fw-bold">Master PO</span>
                                @if ($lastMasterPO)
                                    @php $isTodayPO = \Carbon\Carbon::parse($lastMasterPO)->isToday(); @endphp
                                    <span class="badge rounded-pill {{ $isTodayPO ? 'bg-success' : 'bg-danger' }}">
                                        {{ \Carbon\Carbon::parse($lastMasterPO)->format('d M H:i') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">No Update</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <span class="fw-bold">Master DI</span>
                                @if ($lastMasterDI)
                                    @php $isTodayDI = \Carbon\Carbon::parse($lastMasterDI)->isToday(); @endphp
                                    <span class="badge rounded-pill {{ $isTodayDI ? 'bg-success' : 'bg-danger' }}">
                                        {{ \Carbon\Carbon::parse($lastMasterDI)->format('d M H:i') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">No Update</span>
                                @endif
                            </div>
                        </div>

                        <hr class="my-2">

                        {{-- LIST VENDOR --}}
                        @foreach ($lastUpdates as $row)
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="text-truncate" style="max-width: 120px;">
                                    {{ $row->vendor }}
                                </span>

                                @if ($row->last_update)
                                    @php $isToday = \Carbon\Carbon::parse($row->last_update)->isToday(); @endphp
                                    <span class="badge rounded-pill {{ $isToday ? 'bg-success' : 'bg-danger' }}">
                                        {{ \Carbon\Carbon::parse($row->last_update)->format('d M H:i') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">
                                        No Update
                                    </span>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa !important;
            overflow: hidden;
        }

        .card {
            border-radius: 6px;
        }

        .card-header {
            border-radius: 6px 6px 0 0 !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.15) !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        #autoScrollTable {
            overflow-y: auto !important;
            scroll-behavior: smooth;
            height: 100% !important;
        }

        .table-responsive.h-100 {
            overflow: visible !important;
        }

        #autoScrollTable::-webkit-scrollbar,
        #autoScrollUpdate::-webkit-scrollbar {
            display: none;
        }

        #autoScrollTable,
        #autoScrollUpdate {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script>
        // PIE CHART
        @foreach($pieData as $vendor => $data)
            const ctx{{ $loop->index }} = document.getElementById('chart-{{ $loop->index }}');

            new Chart(ctx{{ $loop->index }}, {
                type: 'doughnut',
                data: {
                    labels: ['OK', 'NG'],
                    datasets: [{
                        data: [{{ $data['OK'] }}, {{ $data['NG'] }}],
                        backgroundColor: ['#00DFA2', '#FF5FCF'],
                        borderWidth: 0.5,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '40%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },
                        datalabels: {
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 9
                            },
                            formatter: function (value) {
                                return value > 0 ? value : '';
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        @endforeach


        // BAR CHART (STACKED)
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

        const closedData = [
            @foreach($barData as $vendor => $data)
                {{ $data['closed'] }},
            @endforeach
                                    ];

        const barCtx = document.getElementById('barVendorChart');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [
                    {
                        label: 'DI Delay',
                        data: delayData,
                        backgroundColor: '#ff3b3b',
                        borderRadius: 2,
                        stack: 'total'
                    },
                    {
                        label: 'DI Closed',
                        data: closedData,
                        backgroundColor: '#c9e933',
                        borderRadius: 2,
                        stack: 'total'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    datalabels: {
                        color: '#495057',
                        font: { size: 9, weight: 'bold' },
                        anchor: 'center',
                        align: 'center',
                        formatter: function (value) {
                            return value > 0 ? value : '';
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: {
                            color: '#495057',
                            font: { size: 8, weight: 'bold' },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total DI (H-1)'
                        },
                        ticks: {
                            precision: 0,
                            font: { size: 7 }
                        }
                    }
                },
                animation: { duration: 0 }
            },
            plugins: [ChartDataLabels]
        });

        setTimeout(function () {
            location.reload();
        }, 300000);

    </script>

    <script>
        function startAutoScroll(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            let scrollSpeed = 0.7;
            let isPaused = false;
            let isAtBottom = false;
            let scrollInterval = null;

            container.addEventListener('mouseenter', () => isPaused = true);
            container.addEventListener('mouseleave', () => isPaused = false);

            function startScrolling() {
                if (scrollInterval) clearInterval(scrollInterval);

                scrollInterval = setInterval(() => {
                    if (isPaused || isAtBottom) return;

                    container.scrollTop += scrollSpeed;

                    const atBottom = container.scrollTop + container.clientHeight >= container.scrollHeight - 5;

                    if (atBottom) {
                        isAtBottom = true;

                        setTimeout(() => {
                            container.scrollTop = 0;

                            setTimeout(() => {
                                isAtBottom = false;
                            }, 3000);
                        }, 2000);
                    }
                }, 16);
            }

            setTimeout(() => {
                startScrolling();
            }, 1000);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                startAutoScroll('autoScrollTable');
                startAutoScroll('autoScrollUpdate');
            });
        } else {
            startAutoScroll('autoScrollTable');
            startAutoScroll('autoScrollUpdate');
        }
    </script>

@endsection