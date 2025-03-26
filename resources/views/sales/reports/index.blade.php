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
                        @if(Auth::user()->role == 'sales' && now()->format('H') < 21 && !$reports->where('created_at', '>=', now()->startOfDay())->count())
                        <a href="{{ route('sales.reports.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Laporan Baru
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="daily-report">
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
                                @if(Auth::user()->role != 'sales')
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
                                            @if(Auth::user()->role === 'ceo' || Auth::user()->role === 'direktur')
                                                <form action="{{ route('sales.reports.unlock', $report) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-{{ $report->is_locked ? 'primary' : 'secondary' }}"
                                                            onclick="return confirm('Apakah Anda yakin ingin membuka kunci laporan ini?')"
                                                            title="Buka Kunci"
                                                            {{ $report->is_locked ? '' : 'disabled' }}>
                                                        <i class="fas fa-{{ $report->is_locked ? 'lock' : 'unlock' }}"></i>
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
                                @else
                                    @forelse($reports as $report)
                                    <tr>
                                        <td>{{ $report->created_at->format('d M Y') }}</td>
                                        <td>Rp {{ number_format($antrians->get($report->created_at->format('Y-m-d'))?->daily_omset?? 0,0, ',', '.') }}</td>
                                        <td>{{ $report->activities->count() }} Aktivitas</td>
                                        <td>{{ $report->offers->count() }} Penawaran</td>
                                        <td>
                                            @php
                                                $isAfter9PM = now()->format('H') >= 21;
                                                $isToday = $report->created_at->isToday();
                                                $isLocked = $report->is_locked === 1;
                                                $isDisabled = ($isAfter9PM || !$isToday) && $isLocked;
                                            @endphp

                                            <a href="{{ route('sales.reports.show', $report) }}" class="btn btn-sm btn-info" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(!$isDisabled)
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
                                            @else
                                                <button class="btn btn-sm btn-warning" disabled title="Tidak dapat diubah">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" disabled title="Tidak dapat dihapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada laporan</td>
                                    </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#daily-report').DataTable();
        });
    </script>
@endpush
