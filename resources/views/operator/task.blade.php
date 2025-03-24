@extends('layouts.app')

@section('title', 'Tugas Saya')

@section('content')
@includeIf('partials.messages')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tugas Kamu</h3>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="taskTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">Semua ({{ $tasks->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="rekanan-tab" data-toggle="tab" href="#rekanan" role="tab" aria-controls="rekanan" aria-selected="false">Rekanan ({{ $rekanans->count() }})</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="taskTabsContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <table id="myTasks" class="table table-bordered table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Nomor Tiket</th>
                                <th>Pelanggan</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Batas Waktu</th>
                                <th>Workshop</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->ticket_order }}</td>
                                <td>{{ $task->customer->nama }}</td>
                                <td>{{ $task->job->job_name }}</td>
                                <td>
                                    <span class="countdown" data-countdown="{{ $task->end_job }}">Loading...</span>
                                </td>
                                <td>{{ $task->working_at }}</td>
                                <td>
                                    @if($task->status == '0')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($task->status == '1')
                                        <span class="badge badge-primary">Sedang Dikerjakan</span>
                                    @elseif($task->status == '2')
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('antrian.estimator-produksi', $task->ticket_order) }}"
                                           class="btn btn-sm btn-info"
                                           target="_blank"
                                           title="Lihat Detail">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                        @if($task->status == '1')
                                            <button type="button"
                                                    class="btn btn-sm btn-success ml-1"
                                                    data-toggle="modal"
                                                    data-target="#confirmModal"
                                                    data-ticket="{{ $task->ticket_order }}"
                                                    title="Tandai Selesai">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="rekanan" role="tabpanel" aria-labelledby="rekanan-tab">
                            <table id="rekananTasks" class="table table-bordered table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nomor Tiket</th>
                                        <th>Pelanggan</th>
                                        <th>Jenis Pekerjaan</th>
                                        <th>Jumlah</th>
                                        <th>Batas Waktu</th>
                                        <th>Workshop</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekanans as $task)
                                    <tr>
                                        <td>{{ $task->ticket_order }}</td>
                                        <td>{{ $task->customer->nama }}</td>
                                        <td>{{ $task->job->job_name }}</td>
                                        <td>{{ $task->qty }}</td>
                                        <td>
                                            <span class="countdown" data-countdown="{{ $task->end_job }}">Loading...</span>
                                        </td>
                                        <td>{{ $task->working_at }}</td>
                                        <td>
                                            @if($task->status == '0')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($task->status == '1')
                                                <span class="badge badge-primary">Sedang Dikerjakan</span>
                                            @elseif($task->status == '2')
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('antrian.estimator-produksi', $task->ticket_order) }}"
                                                    class="btn btn-sm btn-info"
                                                    target="_blank"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                                @if($task->status == '1')
                                                    <button type="button"
                                                            class="btn btn-sm btn-success ml-1"
                                                            data-toggle="modal"
                                                            data-target="#confirmModal"
                                                            data-ticket="{{ $task->ticket_order }}"
                                                            title="Tandai Selesai">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
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
    </div>
</div>


@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-countdown@2.2.0/dist/jquery.countdown.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-countdown@2.2.0/dist/jquery.countdown.min.js"></script>
<script>
    // Handle confirmation with SweetAlert2
    $('button[data-toggle="modal"]').on('click', function() {
        var ticket = $(this).data('ticket');
        Swal.fire({
            title: 'Konfirmasi Selesai',
            text: 'Apakah Anda yakin ingin menandai tugas ini sebagai selesai?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/task/' + ticket + '/complete',
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Berhasil!',
                            'Tugas telah ditandai selesai.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan. Silakan coba lagi.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Function to initialize countdown timers
    function initializeCountdowns() {
        $('[data-countdown]').each(function() {
            var $this = $(this);
            var finalDate = $(this).data('countdown');

            // Safely destroy any existing countdown
            try {
                if ($this.data('countdown-instance')) {
                    $this.countdown('destroy');
                }
            } catch (e) {
                console.log('Cleanup error:', e);
            }

            // Reset the instance flag
            $this.removeData('countdown-instance');

            // Initialize new countdown
            try {
                var countDate = new Date(finalDate);
                if (isNaN(countDate.getTime())) {
                    throw new Error('Invalid date');
                }

                $this.countdown(countDate)
                    .on('update.countdown', function(event) {
                        if (event.offset.totalDays > 0) {
                            $this.html(event.strftime('%D hari %H:%M:%S'));
                        } else {
                            $this.html(event.strftime('%H:%M:%S'));
                        }
                    })
                    .on('finish.countdown', function() {
                        $this.html('<span class="text-danger">Terlambat</span>');
                    });
                $this.data('countdown-instance', true);
            } catch (error) {
                console.error('Error initializing countdown:', error);
                $this.html('Invalid date');
            }
        });
    }

    // DataTable initialization
    $(function () {
        var commonConfig = {
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "drawCallback": function(settings) {
                // Clear any existing countdowns in the table
                var api = this.api();
                var tbody = $(api.table().body());
                tbody.find('.countdown').each(function() {
                    try {
                        $(this).countdown('destroy');
                    } catch (e) {}
                    $(this).removeData('countdown-instance');
                });

                // Initialize new countdowns
                setTimeout(function() {
                    initializeCountdowns();
                }, 50);
            }
        };

        var table = $('#myTasks').DataTable(commonConfig);
        var rekananTable = $('#rekananTasks').DataTable(commonConfig);

        // Handle tab changes
        $('#taskTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
            var targetTab = $(this).attr('href');
            setTimeout(function() {
                if (targetTab === '#rekanan') {
                    rekananTable.draw(false);
                } else {
                    table.draw(false);
                }
            }, 50);
        });

        // Initial countdown initialization
        initializeCountdowns();
    });
</script>
@endpush