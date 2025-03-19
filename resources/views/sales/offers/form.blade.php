<form action="{{ isset($offer) ? route('sales.offers.update', $offer) : route('sales.offers.store') }}" method="POST">
    @csrf
    @if(isset($offer))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="job_id">Produk</label>
                <select name="job_id" id="job_id" class="form-control @error('job_id') is-invalid @enderror" required>
                    <option value="">Pilih Produk</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ (old('job_id', isset($offer) ? $offer->job_id : '')) == $job->id ? 'selected' : '' }}>
                            {{ $job->job_name }}
                        </option>
                    @endforeach
                </select>
                @error('job_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="platform_id">Platform</label>
                <select name="platform_id" id="platform_id" class="form-control @error('platform_id') is-invalid @enderror" required>
                    <option value="">Select Platform</option>
                    @foreach($platforms as $platform)
                        <option value="{{ $platform->id }}" {{ (old('platform_id', isset($offer) ? $offer->platform_id : '')) == $platform->id ? 'selected' : '' }}>
                            {{ $platform->platform_name }}
                        </option>
                    @endforeach
                </select>
                @error('platform_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" name="price" id="price" class="form-control rupiah-input @error('price') is-invalid @enderror" 
                    value="{{ old('price', isset($offer) ? $offer->price : '') }}" style="width: 100%" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="qty">Quantity</label>
                <input type="number" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror" 
                    value="{{ old('qty', isset($offer) ? $offer->qty : '') }}">
                @error('qty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="total">Total</label>
                <input type="number" name="total" id="total" class="form-control rupiah-input @error('total') is-invalid @enderror" 
                    value="{{ old('total', isset($offer) ? $offer->total : '') }}">
                @error('total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Deskripsi</label>
        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($offer) ? $offer->description : '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="updates">Updates</label>
        <textarea name="updates" id="updates" rows="3" class="form-control @error('updates') is-invalid @enderror" placeholder="Pisahkan setiap update dengan tanda koma, contoh:Dalam Pengajuan, Closing">{{ old('updates', isset($offer) ? implode(', ', (array)$offer->updates) : '') }}</textarea>
        @error('updates')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">{{ isset($offer) ? 'Update' : 'Create' }} Offer</button>
        <a href="{{ route('sales.offers.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

@push('styles')
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#job_id').select2({
            placeholder: 'Select Job',
            allowClear: true
        });
        
        $('#platform_id').select2({
            placeholder: 'Select Platform (optional)',
            allowClear: true
        });

        // Calculate total when price or qty changes
        $('#price, #qty').on('input', function() {
            const price = parseFloat($('#price').val()) || 0;
            const qty = parseFloat($('#qty').val()) || 0;
            $('#total').val(price * qty);
        });
    });
</script>
@endpush