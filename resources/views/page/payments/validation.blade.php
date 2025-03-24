@extends('layouts.app')

@section('content')
@includeIf('partials.messages')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Validasi Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($installments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$payment->proof_image) }}" alt="Bukti Pembayaran" class="img-thumbnail" style="max-width: 100px;">
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $payment->status === 'pending' ? 'bg-warning' : ($payment->status === 'approved' ? 'bg-success' : 'bg-danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->status === 'pending')
                                        <form action="{{ route('payments.validate', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                            <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush