@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pembayaran</h3>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-secondary float-end">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th>No. Order</th>
                                    <td>{{ $paymentTransaction->antrian->ticket_order }}</td>
                                </tr>
                                <tr>
                                    <th>Total Pembayaran</th>
                                    <td>Rp {{ number_format($paymentTransaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sisa Pembayaran</th>
                                    <td>Rp {{ number_format($paymentTransaction->calculateRemainingAmount(), 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $paymentTransaction->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $paymentTransaction->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Cicilan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Cicilan</h3>
                    <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addInstallmentModal">
                        <i class="fas fa-plus"></i> Tambah Cicilan
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentTransaction->installments as $installment)
                                <tr>
                                    <td>{{ $installment->created_at->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                                    <td>{{ $installment->payment_method }}</td>
                                    <td>
                                        <span class="badge bg-{{ $installment->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $installment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($installment->proof_file)
                                        <a href="{{ Storage::url($installment->proof_file) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-image"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biaya Tambahan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Biaya Tambahan</h3>
                    <button type="button" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addCostModal">
                        <i class="fas fa-plus"></i> Tambah Biaya
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Biaya</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentTransaction->additionalCosts as $cost)
                                <tr>
                                    <td>{{ $cost->cost_name }}</td>
                                    <td>Rp {{ number_format($cost->cost_amount, 0, ',', '.') }}</td>
                                    <td>{{ $cost->cost_description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Cicilan -->
<div class="modal fade" id="addInstallmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('installments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_transaction_id" value="{{ $paymentTransaction->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Cicilan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="transfer">Transfer Bank</option>
                            <option value="cash">Tunai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" name="proof_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Biaya -->
<div class="modal fade" id="addCostModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('additional-costs.store') }}" method="POST">
                @csrf
                <input type="hidden" name="payment_transaction_id" value="{{ $paymentTransaction->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Biaya</label>
                        <input type="text" class="form-control" name="cost_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="cost_amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="cost_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
