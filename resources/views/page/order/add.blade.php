@extends('layouts.app')

@section('title', 'Desain Baru | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian Desain')

@section('breadcrumb', 'Tambah Desain')

@section('content')

<style>
  .select2-selection {
      height: 34px !important;
  }
</style>

<div class="card card-warning">
  <h5 class="card-header">Tambah Antrian Desain</h5>

  {{-- Tampilkan jika ada error apapun --}}
  @if ($errors->any())
  <div class="alert alert-danger" role="alert">
    <ul>
      {{-- Tampilkan semua error yang ada --}}
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="card-body">
    <form id="formOrder" action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- Inputan untuk judul desain --}}
      <div class="mb-3">
        <label for="title" class="form-label">Judul Project (Keyword)<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Contoh : Es Teh Indonesia" required>
      </div>

      {{-- Input Sales bertipe Hidden --}}
      <input type="hidden" name="sales" value="{{ $sales->id }}">

      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="custom-select rounded-2" name="kategori" id="kategori" required>
            <option selected disabled>--Pilih Kategori--</option>
            <option value="Stempel">Stempel</option>
            <option value="Non Stempel">Non Stempel</option>
            <option value="Advertising">Advertising</option>
            <option value="Digital Printing">Digital Printing</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="job" class="form-label">Jenis Produk <span class="text-danger">*</span></label>
        <select class="custom-select rounded-2" name="job" id="job" required>
            <option value="" default selected disabled>Pilih jenis produk</option>
        </select>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary mb-3" data-toggle="modal" data-target="#exampleModalProduk">
        Tambah Produk
      </button>

      <div class="mb-3">
        <label for="description" class="form-label">Keterangan</label>
        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Contoh : Ukuran 6x6cm, ditambah No. Telp : 08xxxxxxxx"></textarea>
      </div>

      <h6 class="font-weight-bold">Jenis Pekerjaan<span class="text-danger">*</span></h6>
      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio1" value="baru" required>
        <label class="form-check-label" for="inlineRadio1">Desain Baru</label>
      </div>

      <div class="form-check form-check-inline mb-3">
        <input class="form-check-input" type="radio" name="jenisPekerjaan" id="inlineRadio2" value="edit" required>
        <label class="form-check-label" for="inlineRadio2">Edit Desain</label>
      </div>

      {{-- Uplaoad File Referensi Desain --}}
      <div class="mb-3" id="refDesain">
        <h6><strong>File Ref. Desain</strong><span class="text-secondary font-italic"> (.jpeg / .jpg / .png / .cdr )</span></h6>
        <div class="input-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="refDesain" name="refdesain">
            <label class="custom-file-label" for="refDesain"><span class="text-secondary">Pilih File..</span></label>
          </div>
        </div>
      </div>

      {{-- Menangani jika ada error file tidak ditemukan --}}
      @if ($errors->has('design'))
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('design') }}
        </div>
      @endif

      {{-- Checkbox untuk pesanan prioritas / tidak --}}
      <div class="mb-3">
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input custom-control-input-danger" type="checkbox" id="defaultCheck" value="1" name="priority">
          <label class="custom-control-label" for="defaultCheck">
            Prioritas
          </label>
        </div>
      </div>

      {{-- Tombol Submit --}}
        <div class="d-flex align-items-center">
            <input id="btnSubmit" type="submit" class="btn btn-primary submitButton" value="Submit"><div id="loader" class="loader m-2" style="display: none;"></div>
        </div>
    </form>
  </div>
  
  
  {{-- Modal Tambah Jenis Produk --}}
  <div class="modal fade" id="exampleModalProduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalProdukLabel">Tambah Produk Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="produkForm" action="{{ route('tambahProdukByModal') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="modalNamaProduk">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="modalNamaProduk" placeholder="Contoh : Stempel Cup Plastik" name="modalNamaProduk" required>
            </div>
            <div class="form-group">
                <label for="modalJenisProduk">Kategori Produk <span class="text-danger">*</span></label>
                <select class="custom-select rounded-0" id="modalJenisProduk" name="modalJenisProduk" required>
                    <option selected disabled>--Pilih Kategori--</option>
                    <option value="Stempel">Stempel</option>
                    <option value="Advertising">Advertising</option>
                    <option value="Non Stempel">Non Stempel</option>
                    <option value="Digital Printing">Digital Printing</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <input type="submit" class="btn btn-primary submitButton" value="Tambah Produk"><div id="loader" class="loader" style="display: none;"></div>
        </div>
        </form>
        </div>
    </div>
    </div>
</div>
@endsection

@section('script')

<script>
  $(document).ready(function() {
    bsCustomFileInput.init();
    

    $('#kategori').on('change', function() {
      var kategori = $(this).val();

      // Mengosongkan dropdown job
      $('#job').empty(); // Menghapus semua opsi yang ada
        
      // Menambahkan opsi placeholder
      $('#job').append('<option value="">Pilih Jenis Produk</option>');
      
      //select2 job
      $('#job').select2({
        placeholder: 'Pilih Jenis Produk',
        ajax: {
          url: "/get-jobs-by-category/" + kategori,
          type: "GET",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
    
    $('#formOrder').submit(function(e) {
      e.preventDefault();
      var isValid = true;

      // Validasi untuk judul project
      if ($('#title').val().trim() === '') {
        isValid = false;
        $('#title').addClass('is-invalid');
        $('#title').next('.invalid-feedback').remove();
        $('#title').after('<div class="invalid-feedback">Judul Project harus diisi.</div>');
      } else {
        $('#title').removeClass('is-invalid');
        $('#title').next('.invalid-feedback').remove();
      }

      // Validasi untuk kategori
      if ($('#kategori').val() === null) {
        isValid = false;
        $('#kategori').addClass('is-invalid');
        $('#kategori').next('.invalid-feedback').remove();
        $('#kategori').after('<div class="invalid-feedback">Kategori harus dipilih.</div>');
      } else {
        $('#kategori').removeClass('is-invalid');
        $('#kategori').next('.invalid-feedback').remove();
      }

      // Validasi untuk jenis produk
      if ($('#job').val() === null) {
        isValid = false;
        $('#job').addClass('is-invalid');
        $('#job').next('.invalid-feedback').remove();
        $('#job').after('<div class="invalid-feedback">Jenis produk harus dipilih.</div>');
      } else {
        $('#job').removeClass('is-invalid');
        $('#job').next('.invalid-feedback').remove();
      }

      // Validasi untuk jenis pekerjaan
      if (!$('input[name="jenisPekerjaan"]:checked').val()) {
        isValid = false;
        $('input[name="jenisPekerjaan"]').addClass('is-invalid');
        $('input[name="jenisPekerjaan"]').next('.invalid-feedback').remove();
        $('input[name="jenisPekerjaan"]').last().after('<div class="invalid-feedback">Jenis pekerjaan harus dipilih.</div>');
      } else {
        $('input[name="jenisPekerjaan"]').removeClass('is-invalid');
        $('input[name="jenisPekerjaan"]').next('.invalid-feedback').remove();
      }

      // Validasi untuk file referensi desain jika jenis pekerjaan adalah "edit"
      if ($('input[name="jenisPekerjaan"]:checked').val() === 'edit') {
        if ($('#refDesain input[type="file"]').val() === '') {
          isValid = false;
          $('#refDesain input[type="file"]').addClass('is-invalid');
          $('#refDesain input[type="file"]').next('.invalid-feedback').remove();
          $('#refDesain input[type="file"]').after('<div class="invalid-feedback">File referensi desain harus diunggah.</div>');
        } else {
          $('#refDesain input[type="file"]').removeClass('is-invalid');
          $('#refDesain input[type="file"]').next('.invalid-feedback').remove();
        }
      }

      if (isValid) {
        $('#formOrder').off('submit').submit();
      }
    });

    $('#produkForm').submit(function(e) {
      e.preventDefault();
      var modalNamaProduk = $('#modalNamaProduk').val();
      var modalJenisProduk = $('#modalJenisProduk').val();

      if(modalNamaProduk == '' || modalJenisProduk == '') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap isi semua inputan!',
        });
        $('.submitButton').attr('disabled', false);
        $('.loader').hide();
        return false;
      }

      $.ajax({
        url: "{{ route('tambahProdukByModal') }}",
        type: "POST",
        data: {
          "_token": "{{ csrf_token() }}",
          "namaProduk": modalNamaProduk,
          "jenisProduk": modalJenisProduk,
        },
        success: function(data) {
          $('#exampleModalProduk').modal('hide');
          //menghapus inputan pada modal
            $('#modalNamaProduk').val('');
            $('#modalJenisProduk').val('');
          //muncul sweetalert2 success
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                showConfirmButton: false,
                timer: 3000
            });

            //reload halaman
            setInterval(() => {
                location.reload();
            }, 2500);

        },
        error: function(data) {
          //muncul sweetalert2 error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
            });
        }
      });
    });
  });
</script>
@endsection