@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pelanggan</h3>
                    <div class="card-tools">
                        <a href="{{ route('customer.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Nama</th>
                                    <td>{{ $customer->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $customer->telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $customer->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Instansi</th>
                                    <td>{{ $customer->instansi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Info Pelanggan</th>
                                    <td>{{ $customer->infoPelanggan }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Status Follow Up</th>
                                    <td>
                                        @if($customer->status_follow_up == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($customer->status_follow_up == 'done')
                                            <span class="badge badge-success">Done</span>
                                        @else
                                            <span class="badge badge-danger">Ignored</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Frekuensi Order</th>
                                    <td>
                                        {{ $customer->frekuensi_order }}x
                                        @if($customer->frekuensi_order == 0)
                                            <span class="badge badge-info">New Lead</span>
                                        @elseif($customer->frekuensi_order == 1)
                                            <span class="badge badge-primary">New Customer</span>
                                        @else
                                            <span class="badge badge-success">Repeat Order</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Follow Up</th>
                                    <td>{{ $customer->last_follow_up ? date('d M Y H:i', strtotime($customer->last_follow_up)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Next Follow Up</th>
                                    <td>{{ $customer->next_follow_up ? date('d M Y H:i', strtotime($customer->next_follow_up)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Reason for Follow Up</th>
                                    <td>{{ $customer->reason_for_follow_up ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($customer->antrians->count() > 0)
                    <div class="mt-4">
                        <h4>Riwayat Order</h4>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Order</th>
                                    <th>Ticket Order</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->antrians as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>
                                    <td>{{ $order->ticket_order }}</td>
                                    <td>
                                        @if($order->status == 1)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($order->status == 2)
                                            <span class="badge badge-info">Process</span>
                                        @else
                                            <span class="badge badge-success">Complete</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('antrian.estimator-produksi', $order->ticket_order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection