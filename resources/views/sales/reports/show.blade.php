@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Report Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.reports.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Basic Information</h4>
                            <table class="table">
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Sales Name</th>
                                    <td>{{ $report->sales->sales_name }}</td>
                                </tr>
                                <tr>
                                    <th>Daily Omset</th>
                                    <td>Rp {{ number_format($report->omset, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Activities</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Activity Type</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->activities as $activity)
                                        <tr>
                                            <td>{{ $activity->activityType->name }}</td>
                                            <td>{{ $activity->description }}</td>
                                            <td>{{ $activity->amount }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No activities recorded</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Offers</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Job</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Updates</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report->offers as $dailyOffer)
                                        <tr>
                                            <td>{{ $dailyOffer->offer->job->name }}</td>
                                            <td>Rp {{ number_format($dailyOffer->offer->price, 0, ',', '.') }}</td>
                                            <td>
                                                @if($dailyOffer->is_prospect)
                                                    <span class="badge badge-warning">Prospect</span>
                                                @else
                                                    <span class="badge badge-info">Following Up</span>
                                                @endif
                                            </td>
                                            <td>{{ $dailyOffer->updates }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No offers recorded</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection