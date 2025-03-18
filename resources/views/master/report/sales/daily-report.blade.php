@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daily Report Form</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('report.sales.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Daily Report Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="omset">Daily Omset</label>
                                    <input type="number" class="form-control" id="omset" name="omset" required>
                                </div>
                            </div>

                            <!-- Activities Section -->
                            <div class="col-12 mt-4">
                                <h4>Daily Activities</h4>
                                <div id="activities-container">
                                    <div class="row activity-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Activity Type</label>
                                                <select class="form-control" name="activities[0][activity_type_id]" required>
                                                    <option value="">Select Activity Type</option>
                                                    @foreach($activityTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" class="form-control" name="activities[0][description]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="number" class="form-control" name="activities[0][amount]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-activity" style="display: none;">×</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary mt-2" id="add-activity">Add Activity</button>
                            </div>

                            <!-- Offers Section -->
                            <div class="col-12 mt-4">
                                <h4>Daily Offers</h4>
                                <div id="offers-container">
                                    <div class="row offer-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Job ID</label>
                                                <input type="text" class="form-control" name="offers[0][job_id]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Price</label>
                                                <input type="number" class="form-control" name="offers[0][price]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" class="form-control" name="offers[0][qty]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Platform</label>
                                                <select class="form-control" name="offers[0][platform_id]" required>
                                                    <option value="">Select Platform</option>
                                                    @foreach($platforms as $platform)
                                                        <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Is Prospect</label>
                                                <select class="form-control" name="offers[0][is_prospect]">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-offer" style="display: none;">×</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary mt-2" id="add-offer">Add Offer</button>
                            </div>

                            <!-- Ads Report Section -->
                            <div class="col-12 mt-4">
                                <h4>Ads Report</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Platform</label>
                                            <select class="form-control" name="ads_report[platform_id]" required>
                                                <option value="">Select Platform</option>
                                                @foreach($platforms as $platform)
                                                    <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Name</label>
                                            <input type="text" class="form-control" name="ads_report[job_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Amount</label>
                                            <input type="number" class="form-control" name="ads_report[lead_amount]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Total Omset</label>
                                            <input type="number" class="form-control" name="ads_report[total_omset]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Analysis</label>
                                            <textarea class="form-control" name="ads_report[analisa]" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Problems</label>
                                            <textarea class="form-control" name="ads_report[kendala]" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Problems Section -->
                            <div class="col-12 mt-4">
                                <h4>General Problems</h4>
                                <div class="form-group">
                                    <textarea class="form-control" name="problems[problem]" rows="3" placeholder="List any general problems or challenges encountered today"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Submit Report</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Add Activity Row
        $('#add-activity').click(function() {
            const newRow = $('.activity-row').first().clone();
            const index = $('.activity-row').length;

            newRow.find('select, input').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[0]', `[${index}]`));
                $(this).val('');
            });

            newRow.find('.remove-activity').show();
            $('#activities-container').append(newRow);
        });

        // Remove Activity Row
        $(document).on('click', '.remove-activity', function() {
            $(this).closest('.activity-row').remove();
        });

        // Add Offer Row
        $('#add-offer').click(function() {
            const newRow = $('.offer-row').first().clone();
            const index = $('.offer-row').length;

            newRow.find('select, input').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[0]', `[${index}]`));
                $(this).val('');
            });

            newRow.find('.remove-offer').show();
            $('#offers-container').append(newRow);
        });

        // Remove Offer Row
        $(document).on('click', '.remove-offer', function() {
            $(this).closest('.offer-row').remove();
        });
    });
</script>
@endpush
@endsection