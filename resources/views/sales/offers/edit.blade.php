@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Offer</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.offers.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('sales.offers.form', ['offer' => $offer])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection