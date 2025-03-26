@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Offers</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.offers.create') }}" class="btn btn-primary">+ Add New Offer</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Pekerjaan</th>
                                    <th>Platform</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Deskripsi</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($offers as $offer)
                                    <tr>
                                        <td>{{ $offer->job->job_name }}</td>
                                        <td>{{ $offer->platform->platform_name ?? '-' }}</td>
                                        <td>{{ number_format($offer->price, 0) }}</td>
                                        <td>{{ $offer->qty ?? '-' }}</td>
                                        <td>{{ $offer->total ? number_format($offer->total, 0) : '-' }}</td>
                                        <td>{{ Str::limit($offer->description, 30) }}</td>
                                        <td>
                                            <a href="{{ route('sales.offers.edit', $offer) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('sales.offers.destroy', $offer) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this offer?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No offers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $offers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection