@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Export Laporan Penjualan Sales Tertentu</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('export.sales.tertentu') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sales_id">Pilih Sales</label>
                        <select name="sales_id" id="sales_id" class="form-control select2" required>
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                            <option value="{{ $s->id }}">{{ $s->sales_name }}</option>
                            @endforeach
                        </select>
                        @error('sales_id')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_date">Tanggal Awal</label>
                        <input type="date" name="start_date" id="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}"
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('start_date')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}"
                               min="{{ old('start_date') }}"
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('end_date')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize select2
    $('.select2').select2({
        placeholder: 'Pilih Sales',
        allowClear: true
    });

    // Date validation
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });
</script>
@endsection
