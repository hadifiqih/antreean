@extends('layouts.app')

@section('content')
@includeIf('partials.messages')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pembayaran</h3>
                    <div class="card-tools">
                        <a href="{{ route('payments.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th>No. Order</th>
                                    <td><a href="{{ route('antrian.estimator-produksi', $paymentTransaction->antrian->ticket_order) }}"> {{ $paymentTransaction->antrian->ticket_order }} <i class="fas fa-share"></i></a></td>
                                </tr>
                                <tr>
                                    <th>Total Transaksi</th>
                                    <td>Rp {{ number_format($paymentTransaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sisa Pembayaran</th>
                                    <td>Rp {{ number_format($paymentTransaction->calculateRemainingAmount(), 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($paymentTransaction->payment_status === 'unpaid')
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @elseif($paymentTransaction->payment_status === 'partially_paid')
                                            <span class="badge bg-info">Cicilan Sebagian</span>
                                        @elseif($paymentTransaction->payment_status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @endif
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
                    <h3 class="card-title">Riwayat Pembayaran</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary float-end" data-toggle="modal" data-target="#addInstallmentModal">
                            <i class="fas fa-plus"></i> Tambah Pembayaran
                        </button>
                    </div>
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
                                    <td>{{ ucwords($installment->payment_method) }}</td>
                                    <td>
                                        <span class="bg-{{ $installment->validated_by != null ? 'success' : 'warning' }}">{{ $installment->validated_by != null ? 'Diverifikasi oleh' . $installment->validatedBy->name : 'Dalam Pengecekan' }}</span>
                                    </td>
                                    <td>
                                        @if($installment->proof_file)
                                        <a href="{{ asset('storage/bukti-pembayaran/' . $installment->proof_file) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-image"></i>
                                        </a>
                                        @else
                                        <span class="text-muted">Tidak ada bukti</span>
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
                    <div class="card-tools">
                        <!-- Tombol Tambah Biaya -->
                        <button type="button" class="btn btn-sm btn-primary float-end" data-toggle="modal" data-target="#addCostModal">
                            <i class="fas fa-plus"></i> Tambah Biaya
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Biaya</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentTransaction->additionalCosts as $cost)
                                <tr>
                                    @php
                                        $costName = [
                                            'packing' => 'Biaya Packing',
                                            'shipping' => 'Biaya Pengiriman',
                                            'installation' => 'Biaya Pemasangan',
                                        ];
                                    @endphp
                                    <td>{{ $costName[$cost->type] ?? $cost->type }}</td>
                                    <td>Rp {{ number_format($cost->amount, 0, ',', '.') }}</td>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Biaya</label>
                        <select class="form-control" name="type" required>
                            <option value="" disabled selected>Pilih Jenis Biaya</option>
                            <option value="packing">Biaya Packing</option>
                            <option value="shipping">Biaya Pengiriman</option>
                            <option value="installation">Biaya Pemasangan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
