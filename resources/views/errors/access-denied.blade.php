@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Access Denied</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-circle text-danger" style="font-size: 48px;"></i>
                    </div>
                    <h4>{{ $message ?? 'You do not have permission to access this resource.' }}</h4>
                    <p class="text-muted">Please contact your administrator if you believe this is a mistake.</p>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection