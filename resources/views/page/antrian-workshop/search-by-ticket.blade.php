@extends('layouts.app')

@section('content')
@include('partials.messages')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
            <h1>Cari Order</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('antrian.resultCariOrder') }}" method="GET">
                        <div class="form-group">
                            <label for="ticket">Tiket Order</label>
                            <input type="text" name="ticket" class="form-control" placeholder="Masukkan Tiket Order">
                        </div>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection