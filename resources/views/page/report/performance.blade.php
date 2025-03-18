@extends('layouts.app')

@section('title', 'Performance Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee Performance Report - {{ now()->format('F Y') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Position</th>
                                    <th>Operator Tasks</th>
                                    <th>Finisher Tasks</th>
                                    <th>QC Tasks</th>
                                    <th>Total Tasks</th>
                                    <th>Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyStats as $stat)
                                    <tr>
                                        <td>{{ $stat['employee']->name }}</td>
                                        <td>{{ $stat['employee']->position }}</td>
                                        <td>
                                            {{ $stat['operator_tasks']['completed'] }}/{{ $stat['operator_tasks']['total'] }}
                                            @if($stat['operator_tasks']['total'] > 0)
                                                <div class="progress">
                                                    <div class="progress-bar bg-info" role="progressbar" 
                                                        style="width: {{ ($stat['operator_tasks']['completed'] / $stat['operator_tasks']['total']) * 100 }}%" 
                                                        aria-valuenow="{{ ($stat['operator_tasks']['completed'] / $stat['operator_tasks']['total']) * 100 }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $stat['finisher_tasks']['completed'] }}/{{ $stat['finisher_tasks']['total'] }}
                                            @if($stat['finisher_tasks']['total'] > 0)
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                        style="width: {{ ($stat['finisher_tasks']['completed'] / $stat['finisher_tasks']['total']) * 100 }}%" 
                                                        aria-valuenow="{{ ($stat['finisher_tasks']['completed'] / $stat['finisher_tasks']['total']) * 100 }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $stat['qc_tasks']['completed'] }}/{{ $stat['qc_tasks']['total'] }}
                                            @if($stat['qc_tasks']['total'] > 0)
                                                <div class="progress">
                                                    <div class="progress-bar bg-warning" role="progressbar" 
                                                        style="width: {{ ($stat['qc_tasks']['completed'] / $stat['qc_tasks']['total']) * 100 }}%" 
                                                        aria-valuenow="{{ ($stat['qc_tasks']['completed'] / $stat['qc_tasks']['total']) * 100 }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $stat['completed_tasks'] }}/{{ $stat['total_tasks'] }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" 
                                                    style="width: {{ $stat['completion_rate'] }}%" 
                                                    aria-valuenow="{{ $stat['completion_rate'] }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                    {{ $stat['completion_rate'] }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No performance data available</td>
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
@endsection