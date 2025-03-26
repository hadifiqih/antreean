<form action="{{ isset($report) ? route('sales.reports.update', $report) : route('sales.reports.store') }}" method="POST">
    @csrf
    @if(isset($report))
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Aktifitas</h4>
        </div>
        <div class="card-body">
            <div id="activities-container">
                @if(isset($report))
                    @foreach($report->activities as $index => $activity)
                        <div class="row activity-row mb-3">
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <select name="activities[{{ $index }}][activity_type_id]" class="form-control" required>
                                    @foreach($activityTypes as $type)
                                        <option value="{{ $type->id }}" {{ $activity->activity_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <input type="text" name="activities[{{ $index }}][description]" class="form-control"
                                    placeholder="Description" value="{{ $activity->description }}" required>
                            </div>
                            <div class="col-10 col-md-3 mb-2 mb-md-0">
                                <input type="number" name="activities[{{ $index }}][amount]" class="form-control"
                                    placeholder="Amount" value="{{ $activity->amount }}" required>
                            </div>
                            <div class="col-2 col-md-1">
                                <button type="button" class="btn btn-danger w-100 remove-activity">×</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row activity-row mb-3">
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <select name="activities[0][activity_type_id]" class="form-control" required>
                                <option value="">Pilih Aktifitas</option>
                                @foreach($activityTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <input type="text" name="activities[0][description]" class="form-control"
                                placeholder="Deskripsi" required>
                        </div>
                        <div class="col-10 col-md-3 mb-2 mb-md-0">
                            <input type="number" name="activities[0][amount]" class="form-control"
                                placeholder="Jumlah Pelanggan" required>
                        </div>
                        <div class="col-2 col-md-1">
                            <button type="button" class="btn btn-danger w-100 remove-activity">×</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-success" id="add-activity">Tambah Aktifitas</button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Penawaran</h4>
        </div>
        <div class="card-body">
            <div id="offers-container">
                @if(isset($report))
                    @foreach($report->offers as $index => $dailyOffer)
                        <div class="row offer-row mb-3">
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <select name="offers[{{ $index }}][id]" class="form-control" required>
                                    @foreach($offers as $offer)
                                        <option value="{{ $offer->id }}" {{ $dailyOffer->offer_id == $offer->id ? 'selected' : '' }}>
                                            {{ $offer->job->job_name }} - Rp{{ number_format($offer->price, 0) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    <div class="form-check me-md-3">
                                        <input type="checkbox" name="offers[{{ $index }}][is_prospect]" class="form-check-input"
                                            {{ $dailyOffer->is_prospect ? 'checked' : '' }}>
                                        <label class="form-check-label">Hot Prospek</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-10 col-md-4 mb-2 mb-md-0">
                                <input type="text" name="offers[{{ $index }}][updates]" class="form-control"
                                    placeholder="Updates" value="{{ isset($dailyOffer->updates) ? implode(', ', $dailyOffer->updates) : '' }}">
                            </div>
                            <div class="col-2 col-md-1">
                                <button type="button" class="btn btn-danger w-100 remove-offer">×</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-success" id="add-offer">Tambah Penawaran</button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Iklan</h4>
        </div>
        <div class="card-body">
            <div class="iklan-container">
            @if(isset($adsReports))
                @foreach ($adsReports as $index => $ad)
                    <div class="row">
                        <div class="form-group col-12 col-md-6 mb-2 mb-md-0">
                            <label for="ads">Pilih Iklan</label>
                            <select name="ads[{{ $index }}][ads_id]" data-ads-id="{{ $ad->ads_id }}" class="form-control select2">
                                @if(!empty($ads))
                                    @foreach($ads as $item)
                                        <option value="{{ $item['id'] }}"
                                            data-platform-id="{{ $item['platform']['id'] }}"
                                            data-job-name="{{ $item['job']['job_name'] }}"
                                            {{ isset($ad) && $ad->ads_id == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['nomor_iklan'] }} - {{ $item['job']['job_name'] }} - {{ $item['platform']['platform_name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" name="ads[{{ $index }}][platform_id]" value="{{ isset($ads) ? $item['platform']['id'] : '' }}">
                            <input type="hidden" name="ads[{{ $index }}][job_name]" value="{{ isset($ads) ? $item['job']['job_name'] : '' }}">
                        </div>
                        <div class="form-group col-12 col-md-3 mb-2 mb-md-0">
                            <label for="lead_amount">Jumlah Lead</label>
                            <input type="number" name="ads[{{ $index }}][lead_amount]" class="form-control"
                                value="{{ isset($ad) ? $ad->lead_amount : '' }}" placeholder="Contoh : 10">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label for="total_omset">Total Closing</label>
                            <input type="number" name="ads[{{ $index }}][total_omset]" class="form-control"
                                value="{{ isset($ad) ? $ad->total_omset : '' }}" placeholder="Contoh : 150000">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="analisa">Analisa</label>
                            <textarea name="ads[{{ $index }}][analisa]" class="form-control" rows="3">{{ isset($ad) ? $ad->analisa : '' }}</textarea>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="kendala">Kendala</label>
                            <textarea name="ads[{{ $index }}][kendala]" class="form-control" rows="3">{{ isset($ad) ? $ad->kendala : '' }}</textarea>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-danger remove-ads">× Hapus</button>
                        </div>
                    </div>
                @endforeach
            @endif
            </div>
            <button type="button" class="btn btn-success" id="add-ads">Tambah</button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Kendala</h4>
        </div>
        <div class="card-body">
            <label for="kendala">Ceritakan kendala kamu hari ini </label>
            <textarea name="kendala" placeholder="Kendala hari ini sulit mendapat ..." class="form-control @error('kendala') is-invalid @enderror" rows="3">{{ $report->kendala ?? old('kendala') }}</textarea>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Agenda Besok</h4>
        </div>
        <div class="card-body">
            <input type="text" name="agendas" class="form-control @error('agendas') is-invalid @enderror"
                value="{{ isset($report) && !empty($report->agendas) ? implode(', ', $report->agendas) : old('agendas') }}"
                placeholder="Contoh : Follow up customer, meeting dengan tim, dll.">
            <small class="form-text text-muted font-italic">*Pisahkan agenda kamu dengan tanda koma.</small>
            @error('agendas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">{{ isset($report) ? 'Update' : 'Simpan' }} Laporan</button>
        <a href="{{ route('sales.reports.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        // Add new activity row
        $('#add-activity').click(function() {
            const index = $('.activity-row').length;
            const template = `
                <div class="row activity-row mb-3">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                        <select name="activities[${index}][activity_type_id]" class="form-control" required>
                            <option value="">Pilih Aktifitas</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                        <input type="text" name="activities[${index}][description]" class="form-control"
                            placeholder="Deskripsi" required>
                    </div>
                    <div class="col-10 col-md-3 mb-2 mb-md-0">
                        <input type="number" name="activities[${index}][amount]" class="form-control"
                            placeholder="Jumlah Pelanggan" required>
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-activity">×</button>
                    </div>
                </div>
            `;
            $('#activities-container').append(template);
        });

        // Add new offer row
        $('#add-offer').click(function() {
            const index = $('.offer-row').length;
            const template = `
                <div class="row offer-row mb-3">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                        <select name="offers[${index}][id]" class="form-control" required>
                            <option value="">Pilih Penawaran</option>
                            @foreach($offers as $offer)
                                <option value="{{ $offer->id }}">
                                    {{ $offer->job->job_name }} - Rp{{ number_format($offer->price, 0) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <div class="form-check me-md-3">
                                <input type="checkbox" name="offers[${index}][is_prospect]" class="form-check-input">
                                <label class="form-check-label">Hot Prospek</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-10 col-md-4 mb-2 mb-md-0">
                        <input type="text" name="offers[${index}][updates]" class="form-control" placeholder="Updates">
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-offer">×</button>
                    </div>
                </div>
            `;
            $('#offers-container').append(template);
        });

        // Add new ads row
        $('#add-ads').click(function() {
            const index = $('.iklan-container .row').length / 2; // Since each ad has 2 rows
            const template = `
                <div class="row">
                    <div class="form-group col-12 col-md-6 mb-2 mb-md-0">
                        <label for="ads">Pilih Iklan</label>
                        <select name="ads[${index}][ads_id]" class="form-control select2">
                            @if(!empty($ads))
                                @foreach($ads as $ad)
                                    <option value="{{ $ad['id'] }}" data-platform-id="{{ $ad['platform']['id'] }}" data-job-name="{{ $ad['job']['job_name'] }}">
                                        {{ $ad['nomor_iklan'] }} - {{ $ad['job']['job_name'] }} - {{ $ad['platform']['platform_name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <input type="hidden" name="ads[${index}][platform_id]" value="">
                        <input type="hidden" name="ads[${index}][job_name]" value="">
                    </div>
                    <div class="form-group col-12 col-md-3 mb-2 mb-md-0">
                        <label for="lead_amount">Jumlah Lead</label>
                        <input type="number" name="ads[${index}][lead_amount]" class="form-control" placeholder="Contoh : 10">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="total_omset">Total Closing</label>
                        <input type="number" name="total_omset[${index}][total_omset]" class="form-control" placeholder="Contoh : 150000">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="analisa">Analisa</label>
                        <textarea name="ads[${index}][analisa]" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="kendala">Kendala</label>
                        <textarea name="ads[${index}][kendala]" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-ads">× Hapus</button>
                    </div>
                </div>
                <hr>
            `;
            $('.iklan-container').append(template);
        });

        // Remove activity row
        $(document).on('click', '.remove-activity', function() {
            $(this).closest('.activity-row').remove();
        });

        // Remove offer row
        $(document).on('click', '.remove-offer', function() {
            $(this).closest('.offer-row').remove();
        });

        // Remove ads row
        $(document).on('click', '.remove-ads', function() {
            $(this).closest('.row').prev('.row').remove(); // Remove the previous row (ads selection)
            $(this).closest('.row').remove(); // Remove the current row (analisa and kendala)
            $('.iklan-container').find('hr').last().remove(); // Remove the last hr element
        });

        // New: Update hidden inputs when ads select changes
        $('.iklan-container').on('change', 'select.select2', function() {
            const selectedOption = $(this).find('option:selected');
            const platformId = selectedOption.data('platform-id') || '';
            const jobName = selectedOption.data('job-name') || '';
            const parent = $(this).closest('.form-group');
            parent.find('input[name*="[platform_id]"]').val(platformId);
            parent.find('input[name*="[job_name]"]').val(jobName);
        });
    });
</script>
@endpush