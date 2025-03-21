@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Aktivitas</h3>
                    <div class="card-tools">
                        <form action="{{ route('sales.reports.summary') }}" method="GET" class="form-inline">
                            <div class="input-group">
                                @if(Auth::user()->role !== 'sales')
                                <select name="sales_id" class="form-control">
                                    <option value="">Semua Sales</option>
                                    @foreach($salesList as $sales)
                                        <option value="{{ $sales->id }}" {{ request('sales_id') == $sales->id ? 'selected' : '' }}>
                                            {{ $sales->sales_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date', date('Y-m-d', strtotime('-30 days'))) }}">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date', date('Y-m-d')) }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Omset</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['monthly_omset'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-tasks"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Aktivitas</span>
                                    <span class="info-box-number">{{ $summary['total_activities'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-file-contract"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Penawaran</span>
                                    <span class="info-box-number">{{ $summary['total_offers'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tingkat Prospek</span>
                                    <span class="info-box-number">{{ number_format($summary['prospect_rate'], 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Distribusi Jenis Aktivitas</h4>
                                </div>
                                <div class="card-body" style="height: 400px;">
                                    @if($summary['activity_types']->isEmpty())
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <h5 class="text-muted">Aktivitas Tidak Ditemukan</h5>
                                        </div>
                                    @else
                                        <canvas id="activityChart"></canvas>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Tren Omset Harian</h4>
                                </div>
                                <div class="card-body" style="height: 400px;">
                                    <canvas id="omsetChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik Jenis Aktivitas
        @if(!$summary['activity_types']->isEmpty())
        new Chart(document.getElementById('activityChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($summary['activity_types']->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($summary['activity_types']->pluck('daily_activities_count')) !!},
                    backgroundColor: [
                        '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
                        '#d2d6de', '#ff851b', '#39cccc', '#605ca8', '#ff4444'
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            boxWidth: 12
                        }
                    }
                }
            },
        });
        @endif

        // Grafik Omset Harian
        new Chart(document.getElementById('omsetChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($summary['daily_omset']->pluck('date')) !!},
                datasets: [{
                    label: 'Omset Harian',
                    data: {!! json_encode($summary['daily_omset']->pluck('omset')) !!},
                    borderColor: '#00a65a',
                    fill: false
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection