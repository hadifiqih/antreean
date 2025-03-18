@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Karyawan</h3>
                    <div class="card-tools">
                        <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Karyawan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="employee-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Divisi</th>
                                <th>No. Telepon</th>
                                <th>Kantor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $key => $employee)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->division }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->office }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script>
    $(function() {
        $("#employee-table").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@endpush