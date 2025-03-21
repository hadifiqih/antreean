@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Laporan Aktivitas Harian</h3>
                    <div class="card-tools d-flex">
                        @if(Auth::user()->role !== 'sales')
                        <form action="{{ route('sales.reports.index') }}" method="GET" class="form-inline mr-2">
                            <select name="sales_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="">Semua Sales</option>
                                @foreach($salesList as $sales)
                                    <option value="{{ $sales->id }}" {{ request('sales_id') == $sales->id ? 'selected' : '' }}>
                                        {{ $sales->sales_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        @endif
                        <a href="{{ route('sales.reports.summary') }}" class="btn btn-info mr-2">
                            <i class="fas fa-chart-bar"></i> Ringkasan
                        </a>
                        @if(Auth::user()->role == 'sales')
                        <a href="{{ route('sales.reports.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Laporan Baru
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Omset</th>
                                    <th>Aktivitas</th>
                                    <th>Penawaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($antrians->get($report->created_at->format('Y-m-d'))?->daily_omset ?? 0 ,0, ',', '.') }}</td>
                                    <td>{{ $report->activities->count() }} Aktivitas</td>
                                    <td>{{ $report->offers->count() }} Penawaran</td>
                                    <td>
                                        <a href="{{ route('sales.reports.show', $report) }}" 
                                           class="btn btn-sm btn-info {{ !request('sales_id') ? 'disabled' : '' }}" 
                                           title="Lihat" 
                                           {{ !request('sales_id') ? 'onclick="return false;"' : '' }}>
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->role == 'sales')
                                        <a href="{{ route('sales.reports.edit', $report) }}" class="btn btn-sm btn-warning" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales.reports.destroy', $report) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada laporan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection