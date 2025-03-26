@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pembayaran</h3>
        </div>
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="paymentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dp-tab" data-toggle="tab" href="#dp" role="tab" aria-controls="dp" aria-selected="true">
                        Pembayaran DP ({{ $downPayment->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="lunas-tab" data-toggle="tab" href="#lunas" role="tab" aria-controls="lunas" aria-selected="false">
                        Pembayaran Lunas ({{ $fullPayment->count() }})
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="paymentTabsContent">
                <!-- DP Tab -->
                <div class="tab-pane fade show active" id="dp" role="tabpanel" aria-labelledby="dp-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dpTable">
                            <thead>
                                <tr>
                                    <th>Tiket</th>
                                    <th>Total Omset</th>
                                    <th>Sisa Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($downPayment as $index => $payment)
                                <tr>
                                    <td>{{ $payment->antrian->ticket_order }}</td>
                                    <td>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($payment->calculateRemainingAmount(), 0, ',', '.') }}</td>
                                    <td>
                                        @if($payment->payment_status === 'unpaid')
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @elseif($payment->payment_status === 'partially_paid')
                                            <span class="badge bg-info">Cicilan Sebagian</span>
                                        @elseif($payment->payment_status === 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lunas Tab -->
                <div class="tab-pane fade" id="lunas" role="tabpanel" aria-labelledby="lunas-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="lunasTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Order</th>
                                    <th>Total Omset</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fullPayment as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payment->antrian->ticket_order }}</td>
                                    <td>Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success">Lunas</span></td>
                                    <td>
                                        <a href="{{ asset('storage/bukti-pembayaran/' . $payment->proof_file) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-image"></i>
                                        </a>
                                    </td>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
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
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function () {
            var commonConfig = {
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            };

            var dpTable = $('#dpTable').DataTable({
                ...commonConfig,
                "order": [[8, "desc"]]
            });

            var lunasTable = $('#lunasTable').DataTable({
                ...commonConfig,
                "order": [[6, "desc"]]
            });

            // Handle tab changes
            $('#paymentTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
                var targetTab = $(this).attr('href');
                setTimeout(function() {
                    if (targetTab === '#lunas') {
                        lunasTable.draw(false);
                    } else {
                        dpTable.draw(false);
                    }
                }, 50);
            });
        });
    </script>
@endpush
