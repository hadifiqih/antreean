@extends('layouts.app')

@section('content')
@include('partials.messages')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penawaran</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.offers.create') }}" class="btn btn-primary">Tambah Penawaran</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="offers">
                            <thead>
                                <tr>
                                    <th>Sales</th>
                                    <th>Pekerjaan</th>
                                    <th>Platform</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offers as $offer)
                                    <tr>
                                        <td>{{ $offer->sales->sales_name }}</td>
                                        <td>{{ $offer->job->job_name }}</td>
                                        <td>{{ $offer->platform->platform_name ?? '-' }}</td>
                                        <td>{{ number_format($offer->price, 0) }}</td>
                                        <td>{{ $offer->qty ?? '-' }}</td>
                                        <td>{{ $offer->total ? number_format($offer->total, 0) : '-' }}</td>
                                        <td>{{ Str::limit($offer->description, 30) }}</td>
                                        <td>{{ $offer->antrian_ticket_order ? 'Closing🎉' : 'Open' }}</td>
                                        <td>
                                            @if($offer->antrian_ticket_order !== null)
                                                <a href="{{ route('antrian.estimator-produksi', $offer->antrian_ticket_order) }}" class="btn btn-sm btn-info">Detail</a>
                                            @else
                                                @if(Auth::user()->role === 'sales')
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown{{ $offer->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Aksi
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="actionDropdown{{ $offer->id }}">
                                                            @if(!$offer->antrian_ticket_order)
                                                                <a class="dropdown-item" href="#" onclick="showCloseModal({{ $offer->id }})">
                                                                    <i class="fas fa-check-circle text-success"></i> Closing
                                                                </a>
                                                            @endif
                                                            <a class="dropdown-item" href="{{ route('sales.offers.edit', $offer) }}">
                                                                <i class="fas fa-edit text-info"></i> Edit
                                                            </a>
                                                            <form action="{{ route('sales.offers.destroy', $offer) }}" method="POST" class="dropdown-item p-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this offer?')">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>No Action</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No offers found</td>
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

<!-- Single Reusable Close Modal -->
<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-labelledby="closeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="closeOfferForm" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="closeModalLabel">Closing Penawaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="antrian_ticket_order">Nomor Tiket Order</label>
                        <input type="text" class="form-control" id="antrian_ticket_order" name="antrian_ticket_order" required>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#offers').DataTable();
    });

    function showCloseModal(offerId) {
        const form = document.getElementById('closeOfferForm');
        form.action = `{{ route('sales.offers.close', '') }}/${offerId}`;
        $('#closeModal').modal('show');
    }
</script>
@endpush
