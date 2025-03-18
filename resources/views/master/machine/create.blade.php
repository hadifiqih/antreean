@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Machine</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="{{ route('machine.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="machine_code">Machine Code</label>
                        <input type="text" class="form-control @error('machine_code') is-invalid @enderror" 
                            id="machine_code" name="machine_code" value="{{ old('machine_code') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <input type="text" class="form-control @error('type') is-invalid @enderror" 
                            id="type" name="type" value="{{ old('type') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            id="description" name="description">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('machine.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection