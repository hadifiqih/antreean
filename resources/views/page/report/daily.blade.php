@extends('layouts.app')
@section('title', 'Daily Report')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-12">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Daily Sales Report - {{ date('d F Y', strtotime($date)) }}</h5>
                            <div class="row mt-4">
                                <div class="col-lg-4 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <span class="avatar-initial rounded bg-label-primary">
                                                        <i class="bx bx-money"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Total Revenue</span>
                                            <h3 class="card-title mb-2">Rp {{ number_format($totalOmset) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <span class="avatar-initial rounded bg-label-success">
                                                        <i class="bx bx-shopping-bag"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Total Orders</span>
                                            <h3 class="card-title mb-2">{{ $totalOrders }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <span class="avatar-initial rounded bg-label-info">
                                                        <i class="bx bx-user"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Total Customers</span>
                                            <h3 class="card-title mb-2">{{ $totalCustomers }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($antrians as $antrian)
                                        <tr>
                                            <td>{{ $antrian->ticket_order }}</td>
                                            <td>{{ $antrian->customer->nama_customer }}</td>
                                            <td>{{ $antrian->job->nama_job }}</td>
                                            <td>{{ $antrian->qty }}</td>
                                            <td>Rp {{ number_format($antrian->omset) }}</td>
                                            <td>
                                                @if($antrian->status == 0)
                                                    <span class="badge bg-label-warning">Pending</span>
                                                @elseif($antrian->status == 1)
                                                    <span class="badge bg-label-primary">In Progress</span>
                                                @else
                                                    <span class="badge bg-label-success">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No orders today</td>
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
    </div>
</div>
@endsection