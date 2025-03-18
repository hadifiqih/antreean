<form action="{{ isset($report) ? route('sales.reports.update', $report) : route('sales.reports.store') }}" method="POST">
    @csrf
    @if(isset($report))
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="omset">Daily Omset</label>
        <input type="number" name="omset" id="omset" class="form-control @error('omset') is-invalid @enderror" required
            value="{{ $report->omset ?? old('omset') }}">
        @error('omset')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Activities</h4>
        </div>
        <div class="card-body">
            <div id="activities-container">
                @if(isset($report))
                    @foreach($report->activities as $index => $activity)
                        <div class="row activity-row mb-3">
                            <div class="col-md-4">
                                <select name="activities[{{ $index }}][activity_type_id]" class="form-control" required>
                                    @foreach($activityTypes as $type)
                                        <option value="{{ $type->id }}" {{ $activity->activity_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="activities[{{ $index }}][description]" class="form-control" 
                                    placeholder="Description" value="{{ $activity->description }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="activities[{{ $index }}][amount]" class="form-control" 
                                    placeholder="Amount" value="{{ $activity->amount }}" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-activity">×</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row activity-row mb-3">
                        <div class="col-md-4">
                            <select name="activities[0][activity_type_id]" class="form-control" required>
                                <option value="">Select Activity Type</option>
                                @foreach($activityTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="activities[0][description]" class="form-control" 
                                placeholder="Description" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="activities[0][amount]" class="form-control" 
                                placeholder="Amount" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-activity">×</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-success" id="add-activity">Add Activity</button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Offers</h4>
        </div>
        <div class="card-body">
            <div id="offers-container">
                @if(isset($report))
                    @foreach($report->offers as $index => $dailyOffer)
                        <div class="row offer-row mb-3">
                            <div class="col-md-4">
                                <select name="offers[{{ $index }}][id]" class="form-control" required>
                                    @foreach($offers as $offer)
                                        <option value="{{ $offer->id }}" {{ $dailyOffer->offer_id == $offer->id ? 'selected' : '' }}>
                                            {{ $offer->job->name }} - Rp{{ number_format($offer->price, 0) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input type="checkbox" name="offers[{{ $index }}][is_prospect]" class="form-check-input"
                                        {{ $dailyOffer->is_prospect ? 'checked' : '' }}>
                                    <label class="form-check-label">Is Prospect</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="offers[{{ $index }}][updates]" class="form-control" 
                                    placeholder="Updates" value="{{ $dailyOffer->updates }}">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-offer">×</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-success" id="add-offer">Add Offer</button>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">{{ isset($report) ? 'Update' : 'Save' }} Report</button>
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
                    <div class="col-md-4">
                        <select name="activities[${index}][activity_type_id]" class="form-control" required>
                            <option value="">Select Activity Type</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="activities[${index}][description]" class="form-control" 
                            placeholder="Description" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="activities[${index}][amount]" class="form-control" 
                            placeholder="Amount" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-activity">×</button>
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
                    <div class="col-md-4">
                        <select name="offers[${index}][id]" class="form-control" required>
                            <option value="">Select Offer</option>
                            @foreach($offers as $offer)
                                <option value="{{ $offer->id }}">
                                    {{ $offer->job->name }} - Rp{{ number_format($offer->price, 0) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="offers[${index}][is_prospect]" class="form-check-input">
                            <label class="form-check-label">Is Prospect</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="offers[${index}][updates]" class="form-control" placeholder="Updates">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-offer">×</button>
                    </div>
                </div>
            `;
            $('#offers-container').append(template);
        });

        // Remove activity row
        $(document).on('click', '.remove-activity', function() {
            $(this).closest('.activity-row').remove();
        });

        // Remove offer row
        $(document).on('click', '.remove-offer', function() {
            $(this).closest('.offer-row').remove();
        });
    });
</script>
@endpush