@extends('layouts.app')

@section('content')

@include('partials.messages')

{{-- Content Table --}}
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
            <form id="filterByCategory" action="{{ route('antrian.filterByCategory') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="kategori">Kategori Pekerjaan</label>
                    <select id="kategori" name="kategori" class="custom-select rounded-1" {{ isset($filtered) ? 'disabled' : '' }}>
                        <option value="Semua">Semua</option>
                        <option value="Stempel" {{ isset($filtered) && $filtered == "Stempel" ? "selected" : "" }}>Stempel</option>
                        <option value="Advertising" {{ isset($filtered) && $filtered == "Advertising" ? "selected" : "" }}>Advertising</option>
                        <option value="Non Stempel" {{ isset($filtered) && $filtered == "Non Stempel" ? "selected" : "" }}>Non Stempel</option>
                        <option value="Digital Printing" {{ isset($filtered) && $filtered == "Digital Printing" ? "selected" : "" }}>Digital Printing</option>
                    </select>
            </div>
            <div class="col-md-2 align-self-end">
                @if(isset($filtered))
                <a href="{{ route('antrian.index') }}" class="btn btn-danger mt-1">Reset</a>
                @else
                <button type="submit" class="btn btn-primary mt-1">Filter</button>
                @endif
            </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mb-2" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Dikerjakan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Selesai</a>
                    </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Antrian Stempel</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrian" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Ticket Order</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Nama Customer</th>
                                            <th scope="col">Jenis Produk</th>
                                            <th scope="col">Deadline</th>
                                            <th scope="col">Desainer</th>
                                            <th scope="col">Tempat Pengerjaan</th>
                                            <th scope="col">Catatan Admin</th>
                                            @if(auth()->user()->role == 'admin')
                                                <th scope="col">Aksi</th>
                                            @elseif(auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising' || auth()->user()->id == '28' || auth()->user()->role == 'estimator' || auth()->user()->role == 'sales' )
                                            <th scope="col">Progress</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($antrians as $antrian)
                                            <tr>
                                                <td>
                                                @if($antrian->end_job == null)
                                                    <p class="text-danger">{{ $antrian->ticket_order }}<i class="fas fa-circle"></i></p>
                                                @else
                                                    <p class="text-success">{{ $antrian->ticket_order }}</p>
                                                @endif
                                                </td>
                                                <td>{{ $antrian->sales->sales_name }}
                                                    @if($antrian->order && $antrian->order->is_priority == 1)
                                                        <span><i class="fas fa-star text-warning"></i></span>
                                                    @endif
                                                </td>
                                                <td>{{ $antrian->customer->nama }}</td>
                                                <td>{{ $antrian->job->job_name }} <a href="{{ route('antrian.estimator-produksi', $antrian->ticket_order) }}" type="button" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-info-circle"></i></a></td>

                                                <td class="text-center">
                                                    <span class="countdown" data-countdown="{{ $antrian->end_job }}">Loading..</span>
                                                </td>

                                                <td>
                                                    {{-- Nama Desainer --}}
                                                    @if($antrian->order->employee_id)
                                                        {{ $antrian->order->employee->name }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>

                                                <td>
                                                    @php
                                                        $tempat = explode(',', $antrian->working_at);
                                                        foreach ($tempat as $item) {
                                                                if($item == 'Surabaya'){
                                                                    if($item == end($tempat)){
                                                                        echo '- Surabaya';
                                                                    }
                                                                    else{
                                                                        echo '- Surabaya' . "<br>";
                                                                    }
                                                                }elseif ($item == 'Kediri') {
                                                                    if($item == end($tempat)){
                                                                        echo '- Kediri';
                                                                    }
                                                                    else{
                                                                        echo '- Kediri' . "<br>";
                                                                    }
                                                                }elseif ($item == 'Malang') {
                                                                    if($item == end($tempat)){
                                                                        echo '- Malang';
                                                                    }
                                                                    else{
                                                                        echo '- Malang' . "<br>";
                                                                    }
                                                                }
                                                            }
                                                    @endphp
                                                </td>
                                                <td>{{ $antrian->admin_note != null ? $antrian->admin_note : "-" }}</td>

                                                @if(auth()->user()->role == 'admin')
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning">Ubah</button>
                                                        <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                            <div class="dropdown-menu" role="menu">
                                                                <a class="dropdown-item" href="{{ url('antrian/'.$antrian->id. '/edit') }}"><i class="fas fa-xs fa-pen"></i> Edit</a>
                                                                <a class="dropdown-item {{ $antrian->end_job ? 'text-warning' : 'disabled' }}" href="{{ route('cetak-espk', $antrian->ticket_order) }}" target="_blank"><i class="fas fa-xs fa-print"></i> Unduh e-SPK</a>
                                                                <a class="dropdown-item {{ $antrian->end_job ? 'text-success' : 'text-muted disabled' }}" href="{{ route('antrian.markSelesai', $antrian->id) }}"><i class="fas fa-xs fa-check"></i> Tandai Selesai</a>
                                                                {{-- <a class="dropdown-item text-danger disabled" href="{{ route('cetak-espk', $antrian->ticket_order) }}" target="_blank"><i class="fas fa-xs fa-print"></i> Cetak e-SPK</a> --}}
                                                                <form
                                                                    action="{{ route('antrian.destroy', $antrian->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data antrian ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item"
                                                                        data-id="{{ $antrian->id }}">
                                                                        <i class="fas fa-xs fa-trash"></i> Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                    </div>
                                                </td>
                                                @endif

                                                @if(auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising' || auth()->user()->role == 'sales' || auth()->user()->id == '28' || auth()->user()->role == 'estimator')
                                                <td>
                                                    @php
                                                        $waktuSekarang = date('H:i');
                                                        $waktuAktif = '15:00';
                                                    @endphp
                                                    <div class="btn-group">
                                                        @if( $waktuSekarang > $waktuAktif )
                                                            @if($antrian->timer_stop != null && $antrian->end_job != null)
                                                                <a href="" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Sip</a>
                                                            @else
                                                                <a type="button" class="btn btn-outline-danger btn-sm" href="{{ route('antrian.showProgress', $antrian->id) }}">Upload</a>
                                                            @endif
                                                        @elseif( $waktuSekarang < $waktuAktif )
                                                            <a type="button" class="btn btn-outline-danger btn-sm disabled" href="#">Belum Aktif</a>
                                                        @endif
                                                        @if($antrian->end_job != null)
                                                            <a href="{{ route('antrian.showDokumentasi', $antrian->id) }}" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Tandai Selesai</a>
                                                        @else
                                                            <a href="" class="btn btn-outline-success btn-sm disabled"><i class="fas fa-check"></i> Tandai Selesai</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if(auth()->user()->role == 'stempel' || auth()->user()->role == 'advertising')
                                    <p class="text-muted font-italic mt-2 text-sm">*Tombol <span class="text-danger">"Upload Progress"</span> akan aktif diatas jam 15.00</p>
                                @endif
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Antrian Stempel</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataAntrianSelesai" class="table table-responsive table-bordered table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Ticket Order</th>
                                            <th scope="col">Keyword Project</th>
                                            <th scope="col">Nama Customer</th>
                                            <th scope="col">Sales</th>
                                            <th scope="col">Jenis Produk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($antrianSelesai as $antrian)
                                            <tr>
                                                <td>{{ $antrian->ticket_order }}</td>
                                                <td>{{ $antrian->order->title ?? '-' }}</td>
                                                <td>{{ $antrian->customer->nama }}</td>
                                                <td>{{ $antrian->sales->sales_name }}
                                                    @if($antrian->order && $antrian->order->is_priority == 1)
                                                        <span><i class="fas fa-star text-warning"></i></span>
                                                    @endif
                                                </td>
                                                <td>{{ $antrian->job->job_name }} <a href="{{ route('antrian.estimator-produksi', $antrian->ticket_order) }}" type="button" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-info-circle"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <!-- /.container-fluid -->
    @foreach ($antrians as $antrian)
    <div class="modal fade" id="modal-accdesain{{ $antrian->id }}">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Preview File Acc Desain</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <img loading="lazy" class="img-fluid" src="storage/acc-desain/{{ $antrian->order->acc_desain }}">
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      @endforeach

      @foreach ($antrianSelesai as $antrian)
        <div class="modal fade" id="modal-accdesain{{ $antrian->id }}">
            <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Preview File Acc Desain</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <img loading="lazy" class="img-fluid" src="storage/acc-desain/{{ $antrian->order->acc_desain }}">
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
      <!-- /.modal -->
      @endforeach

      @foreach ($antrians as $antrian)
        <div class="modal fade" id="modal-buktiPembayaran{{ $antrian->id }}">
            <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">File Bukti Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    {{-- Menampilkan payment_proof dari tabel payments --}}
                    @php
                        $paymentProof = \App\Models\Payment::where('ticket_order', $antrian->ticket_order)->orderBy('created_at', 'desc')->get();
                    @endphp
                    @foreach ($paymentProof as $item)
                        @if($item->payment_proof == null)
                            <p class="text-danger">Tidak ada file</p>
                        @else
                        <img loading="lazy" class="img-fluid" src="storage/bukti-pembayaran/{{ $item->payment_proof }}">
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    @endforeach

    @foreach ($antrianSelesai as $antrian)
        <div class="modal fade" id="modal-buktiPembayaran{{ $antrian->id }}">
            <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">File Bukti Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    {{-- Menampilkan payment_proof dari tabel payments --}}
                    @php
                        $paymentProof = \App\Models\Payment::where('ticket_order', $antrian->ticket_order)->get();
                    @endphp
                    @foreach ($paymentProof as $item)
                    <img loading="lazy" class="img-fluid" src="{{ asset('storage/bukti-pembayaran/'.$item->payment_proof) }}">
                    @endforeach
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    @endforeach

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-countdown@2.2.0/dist/jquery.countdown.min.css">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-countdown@2.2.0/dist/jquery.countdown.min.js"></script>
<script src="{{ asset('adminlte/dist/js/maskMoney.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.maskRupiah').maskMoney({prefix:'Rp ', thousands:'.', decimal:',', precision:0});

            // Initialize DataTable with variable
            var table = $("#dataAntrian").DataTable({
                "responsive": true, 
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "pageLength": 25,
                "drawCallback": function() {
                    // Reinitialize countdowns after each draw
                    initializeCountdowns();
                }
            });

            $("#dataAntrianSelesai").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "pageLength": 25
            });

            // Menutup modal saat modal lainnya dibuka
            $('.modal').on('show.bs.modal', function (e) {
                $('.modal').not($(this)).each(function () {
                    $(this).modal('hide');
                });
            });

            function initializeCountdowns() {
                $('.countdown').each(function() {
                    var $this = $(this);
                    var finalDate = $this.data('countdown');
                    
                    // Clear any existing countdown
                    if ($this.data('countdown-instance')) {
                        try {
                            $this.countdown('remove');
                            $this.removeData('countdown-instance');
                        } catch(e) {
                            console.log('Error removing countdown:', e);
                        }
                    }

                    try {
                        if (finalDate) {
                            $this.countdown(finalDate)
                                .on('update.countdown', function(event) {
                                    if (event.offset.totalDays > 0) {
                                        $(this).html(event.strftime('%D hari %H:%M:%S'));
                                    } else {
                                        $(this).html(event.strftime('%H:%M:%S'));
                                    }
                                })
                                .on('finish.countdown', function() {
                                    $(this).html('<span class="text-danger">TERLAMBAT</span>');
                                });
                            $this.data('countdown-instance', true);
                        } else {
                            $this.html('<span class="text-danger">BELUM DIANTRIKAN</span>');
                        }
                    } catch (error) {
                        console.error('Error initializing countdown:', error);
                        $this.html('<span class="text-danger">BELUM DIANTRIKAN</span>');
                    }
                });
            }

            // Initial countdown initialization
            initializeCountdowns();

            $('.metodePembayaran').on('change', function(){
                var id = $(this).attr('id').split('metodePembayaran')[1];
                var metode = $(this).val();
                if(metode == 'Tunai' || metode == 'Cash'){
                    $('.filePelunasan').hide();
                    $('#filePelunasan'+id).removeAttr('required');
                }
                else{
                    $('.filePelunasan').show();
                    $('#filePelunasan'+id).attr('required', true);
                }
            });

            $('.btnModalPelunasan').on('click', function(){
                //ambil id dari tombol submitUnggahBayar
                var id = $(this).attr('id').split('btnModalPelunasan')[1];

                $('#jumlahPembayaran'+id).on('keyup', function(){
                //ambil value dari jumlahPembayaran
                var jumlah = $('#jumlahPembayaran'+id).val().replace(/Rp\s|\.+/g, '');
                //ambil value dari sisaPembayaran
                var sisa = $('#sisaPembayaran'+id).val().replace(/Rp\s|\.+/g, '');
                //inisialisasi variabel keterangan
                var keterangan = $('#keterangan'+id);
                //inisialisasi variabel submit
                var submit = $('#submitUnggahBayar'+id);
                //jika jumlah pembayaran melebihi sisa pembayaran
                if(parseInt(jumlah) > parseInt(sisa)){
                    //tampilkan keterangan
                    keterangan.html('<span class="text-danger">Jumlah pembayaran melebihi sisa pembayaran</span>');
                    //tombol submit disabled
                    submit.attr('disabled', true);
                }
                else{
                    //sembunyikan keterangan
                    keterangan.html('');
                    //tombol submit enabled
                    submit.attr('disabled', false);
                }
                });
            });
        });
    </script>
@endpush
