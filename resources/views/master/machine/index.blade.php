@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Machines</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('machine.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Machine
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Machine Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($machines as $machine)
                        <tr>
                            <td>{{ $machine->machine_code }}</td>
                            <td>{{ $machine->name }}</td>
                            <td>{{ $machine->type }}</td>
                            <td>{{ $machine->description }}</td>
                            <td>{!! $machine->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}</td>
                            <td>
                                <a href="{{ route('machine.edit', $machine) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('machine.destroy', $machine) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection