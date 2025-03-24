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
                        <table class="table table-bordered" id="payments-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal & Tiket</th>
                                    <th>Nominal</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($installments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <p class="mb-0">{{ $payment->created_at->format('d/m/Y') }}</p>
                                        <p class="mt-0">{{ $payment->paymentTransaction->antrian->ticket_order }}</p>
                                    </td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ asset('storage/bukti-pembayaran/'.$payment->proof_file) }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-image fa-2x"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('payments.confirmValidate', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" data-status="approved" class="btn btn-success btn-sm validate-btn">
                                                <i class="fas fa-check"></i> Terima
                                            </button>
                                            <button type="button" data-status="rejected" class="btn btn-danger btn-sm validate-btn">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                            <input type="hidden" name="status" class="status-input">
                                        </form>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#payments-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            }
        });

        $('.validate-btn').click(function() {
            const form = $(this).closest('form');
            const status = $(this).data('status');
            const statusInput = form.find('.status-input');
            const title = status === 'approved' ? 'Terima Pembayaran' : 'Tolak Pembayaran';
            const text = status === 'approved' ?
                'Apakah Anda yakin ingin menerima pembayaran ini?' :
                'Apakah Anda yakin ingin menolak pembayaran ini?';
            const confirmButtonText = status === 'approved' ? 'Ya, Terima' : 'Ya, Tolak';
            const confirmButtonColor = status === 'approved' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    statusInput.val(status);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush