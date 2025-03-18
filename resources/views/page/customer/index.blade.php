@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Daftar Pelanggan</h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="repeat-order-tab" data-toggle="pill" href="#repeatOrder" role="tab" aria-controls="repeatOrder" aria-selected="true">
                                Repeat Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="new-customer-tab" data-toggle="pill" href="#newCustomer" role="tab" aria-controls="newCustomer" aria-selected="false">
                                Pelanggan Baru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="leads-tab" data-toggle="pill" href="#leads" role="tab" aria-controls="leads" aria-selected="false">
                                Leads
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="customerTabContent">
                        <div class="tab-pane fade show active" id="repeatOrder" role="tabpanel" aria-labelledby="repeat-order-tab">
                            <div class="table-responsive text-nowrap mt-4">
                                <table class="table table-hover" id="repeatOrderTable">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Frekuensi Order</th>
                                            <th>Last Order</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="newCustomer" role="tabpanel" aria-labelledby="new-customer-tab">
                            <div class="table-responsive text-nowrap mt-4">
                                <table class="table table-hover" id="newCustomerTable">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>First Order Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="leads" role="tabpanel" aria-labelledby="leads-tab">
                            <div class="table-responsive text-nowrap mt-4">
                                <table class="table table-hover" id="leadsTable">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Status Follow Up</th>
                                            <th>Next Follow Up</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let tables = {
            repeatOrder: $('#repeatOrderTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.repeat-orders') }}",
                columns: [
                    {data: 'nama', name: 'nama'},
                    {data: 'telepon', name: 'telepon'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'frekuensi_order', name: 'frekuensi_order'},
                    {data: 'last_order_date', name: 'last_order_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            }),
            newCustomer: $('#newCustomerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.new-customers') }}",
                columns: [
                    {data: 'nama', name: 'nama'},
                    {data: 'telepon', name: 'telepon'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            }),
            leads: $('#leadsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.leads') }}",
                columns: [
                    {data: 'nama', name: 'nama'},
                    {data: 'telepon', name: 'telepon'},
                    {data: 'alamat', name: 'alamat'},
                    {data: 'status_follow_up', name: 'status_follow_up'},
                    {data: 'next_follow_up', name: 'next_follow_up'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            })
        };

        // Handle tab show event
        $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
            let targetId = $(e.target).attr('href').replace('#', '');
            if (tables[targetId]) {
                tables[targetId].columns.adjust().draw();
            }
        });
    });
</script>
@endpush
@endsection