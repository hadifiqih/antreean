@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Laporan Aktivitas Harian</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.reports.summary') }}" class="btn btn-info mr-2">
                            <i class="fas fa-chart-bar"></i> Ringkasan
                        </a>
                        <a href="{{ route('sales.reports.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Laporan Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Omset</th>
                                    <th>Activities</th>
                                    <th>Offers</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($report->omset, 0, ',', '.') }}</td>
                                    <td>{{ $report->activities->count() }} activities</td>
                                    <td>{{ $report->offers->count() }} offers</td>
                                    <td>
                                        <a href="{{ route('sales.reports.show', $report) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales.reports.edit', $report) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales.reports.destroy', $report) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No reports found</td>
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