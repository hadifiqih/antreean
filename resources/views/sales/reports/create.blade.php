@extends('layouts.app')

@section('content')
@includeIf('partials.messages')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Laporan</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.reports.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('sales.reports.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection