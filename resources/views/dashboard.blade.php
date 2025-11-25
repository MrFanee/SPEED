@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
  <div class="pagetitle">
    <h1>Dashboard</h1>
  </div>

  <section class="section dashboard">
    <div class="container-fluid py-1">
      <div class="row">
        <!-- 8 cards -->
        <div class="col-lg-8 col-12">
          <div class="row">
            <!-- Total Item -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Total Item</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_item']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Item NG -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Total Item NG</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_ng']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Item OK -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Total Item OK</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_ok']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total On Schedule -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">On Schedule</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_on_schedule']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Material -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Material</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-tools"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_material']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Man -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Man</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person-x"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_man']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Machine -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Machine</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-gear"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_machine']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Method -->
            <div class="col-lg-3 col-md-6 col-sm-6 p-1">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 15px;">Method</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-diagram-3"></i>
                    </div>
                    <div class="ps-3">
                      <h6 style="font-size: 18px;">{{ number_format($cardData['total_method']) }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Last Update Vendor -->
        <div class="col-lg-4 col-12 p-1">
          <div class="card vendor-update-card" style="height: 92%">
            <div class="card-body d-flex flex-column p-2">
              <h5 class="card-title mb-0">Last Update Vendor</h5>
              <div class="list-group flex-grow-1 overflow-auto" style="height:200px">
                @foreach ($lastUpdates as $row)
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 small">{{ $row->vendor }}</h6>
                    @if ($row->last_update)
                      @php
                        $isToday = \Carbon\Carbon::parse($row->last_update)->isToday();
                      @endphp
                      <span class="badge rounded-pill {{ $isToday ? 'bg-primary' : 'bg-danger' }}">
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
    </div>

    <!-- Bar Chart -->
    @if(auth()->user()->role !== 'vendor')
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center fw-bold">Vendor Performance {{ $formattedTanggal }}</h5>
            <canvas id="vendorChart"></canvas>
          </div>
        </div>
      </div>
    @endif

    <!-- Resume Chart -->
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center fw-bold">
            Monitoring 2 Days Stock periode {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
          </h5>
          <canvas id="monthlyResumeChart" style="height:250px;"></canvas>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

  <!-- Bar Chart -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const chartData = @json($chartData);

      const vendors = chartData.map(item => item.vendor);
      const itemNG = chartData.map(item => item.item_ng);
      const itemOK = chartData.map(item => item.item_ok);

      const ctx = document.getElementById('vendorChart').getContext('2d');
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
          labels: vendors,
          datasets: [
            {
              label: 'Item NG',
              data: itemNG,
              backgroundColor: '#F2AEBB'
            },
            {
              label: 'Item OK',
              data: itemOK,
              backgroundColor: '#6DC3BB'
            }
          ]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          // maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top'
            },
            datalabels: {
              color: 'gray',
              anchor: 'end',
              align: 'top',
              formatter: function (value) {
                return value > 0 ? value : '';
              },
              font: {
                weight: 'bold'
              }
            }
          },
          scales: {
            x: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return Number.isInteger(value) ? value : null;
                }
              }
            },
            y: {
              ticks: {
                maxRotation: 0,
                minRotation: 0
              }
            }
          }
        }
      });
    });
  </script>

  <!-- Resume Chart -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const monthlyResume = @json($monthlyResume);

      const labels = monthlyResume.map(r => r.tgl);
      const dataOK = monthlyResume.map(r => r.ok);
      const dataMaterial = monthlyResume.map(r => r.material);
      const dataMan = monthlyResume.map(r => r.man);
      const dataMachine = monthlyResume.map(r => r.machine);
      const dataMethod = monthlyResume.map(r => r.method);
      const totalItems = monthlyResume.map(r => r.total_item);
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

      new Chart(document.getElementById('monthlyResumeChart'), {
        type: 'bar',
        plugins: [ChartDataLabels, chartLegendMargin],
        data: {
          labels: labels,
          datasets: [
            { label: 'Method', data: dataMethod, stack: 'stack1', backgroundColor: '#DDC57A' },
            { label: 'Machine', data: dataMachine, stack: 'stack1', backgroundColor: '#CE7E5A' },
            { label: 'Man', data: dataMan, stack: 'stack1', backgroundColor: '#FCB53B' },
            { label: 'Material', data: dataMaterial, stack: 'stack1', backgroundColor: '#FFE797' },
            { label: 'OK', data: dataOK, stack: 'stack1', backgroundColor: '#89AC46' },

            {
              type: 'line',
              label: 'Total Item',
              data: totalItems,
              borderWidth: 2,
              fill: false,
              yAxisID: 'y'
            }
          ]
        },
        options: {
          responsive: true,
          scales: {
            x: {
              stacked: true,
              ticks: {
                maxRotation: 45,
                minRotation: 45,
                callback: function (value, index, ticks) {
                  const raw = this.getLabelForValue(value); // contoh: 2025-11-12
                  const date = new Date(raw);
                  const day = date.getDate();
                  const month = date.toLocaleString('en-US', { month: 'short' });
                  return `${day}-${month}`; // hasil: 12-Nov
                }
              }
            },
            y: {
              stacked: true,
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return Number.isInteger(value) ? value : null;
                }
              }
            }
          },
          plugins: {
            legend: { position: 'top' },
            datalabels: {
              color: '#BF092F',
              anchor: 'start',
              align: 'top',
              formatter: function (value) {
                return value > 0 ? value : '';
              },
              font: {
                weight: 'bold'
              }
            }
          }
        }
      });

    });
  </script>
@endsection