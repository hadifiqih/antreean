@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Laporan</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.reports.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informasi Dasar</h4>
                            <table class="table">
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Sales</th>
                                    <td>{{ $report->sales->sales_name }}</td>
                                </tr>
                                <tr>
                                    <th>Omset Harian</th>
                                    <td>Rp {{ number_format($report->omset, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h4>Aktivitas</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Jenis Aktivitas</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->activities as $activity)
                                        <tr>
                                            <td>{{ $activity->activityType->name }}</td>
                                            <td>{{ $activity->description }}</td>
                                            <td>{{ $activity->amount }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada aktivitas tercatat</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h4>Penawaran</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Pekerjaan</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Pembaruan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->offers as $dailyOffer)
                                        <tr>
                                            <td>{{ $dailyOffer->offer->job->job_name }}</td>
                                            <td>Rp {{ number_format($dailyOffer->offer->price, 0, ',', '.') }}</td>
                                            <td>
                                                @if($dailyOffer->is_prospect)
                                                    <span class="badge badge-danger">Hot Prospek</span>
                                                @else
                                                    <span class="badge badge-info">Belum Prospek</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dailyOffer->updates)
                                                    <ul class="list-unstyled m-0">
                                                        @foreach($dailyOffer->updates as $update)
                                                            <li>• {{ $update }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada penawaran tercatat</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h4>Penjualan</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Pekerjaan</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Omset</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($antrians as $order)
                                        <tr>
                                            <td>{{ $order->job->job_name }}</td>
                                            <td>Rp {{ number_format($order->harga_produk, 0, ',', '.') }}</td>
                                            <td>{{ $order->qty }}</td>
                                            <td>Rp {{ number_format($order->omset, 0, ',', '.') }}</td>
                                            <td>
                                                @if($order->status == 2)
                                                    <span class="badge badge-success">Selesai</span>
                                                @else
                                                    <span class="badge badge-warning">Sedang Dikerjakan</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada penjualan tercatat</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h4>Laporan Harian Iklan</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Platform</th>
                                            <th>Produk</th>
                                            <th>Jumlah Leads</th>
                                            <th>Total Omset</th>
                                            <th>Analisa</th>
                                            <th>Kendala</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->adsReports as $ad)
                                        <tr>
                                            <td>{{ $ad->platform->platform_name }}</td>
                                            <td>{{ $ad->job_name }}</td>
                                            <td>{{ $ad->lead_amount }}</td>
                                            <td>Rp {{ number_format($ad->total_omset, 0, ',', '.') }}</td>
                                            <td>{{ $ad->analisa }}</td>
                                            <td>{{ $ad->kendala }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada leads tercatat</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h4>Kendala</h4>
                            <p class="bg-gray p-2">{{ $report->kendala }}</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h4>Agenda Besok</h4>
                            @if($report->agendas)
                                <ul class="list-unstyled">
                                    @foreach($report->agendas as $agenda)
                                        <li>• {{ $agenda }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection