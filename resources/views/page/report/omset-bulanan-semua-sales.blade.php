@extends('layouts.app')

@section('page', 'Laporan')

@section('title', 'Omset Bulanan')

@section('breadcrumb', 'Omset Bulanan Semua Sales')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Omset Bulanan Semua Sales</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Sales</th>
                <th>Omset</th>
            </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->sales_name }}</td>
                    <td>Rp {{ number_format($sale->total_omset, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td>Rp {{ number_format($totalOmsetBulanan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
