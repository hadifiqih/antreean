<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('sneat') }}/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - Antree</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('adminlte') }}/dist/img/antree-150x150.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="{{ asset('sneat') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('sneat') }}/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card my-2">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder">Antree</span>
                </a>
              </div>
              <!-- /Logo -->
              @include('partials.messages')
              <h4 class="mb-2">Daftar Akun Antree 🚀</h4>
              <p class="mb-4">Silahkan isi form dibawah guys !</p>

              <form id="formAuthentication" class="mb-3" action="{{ route('auth.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Menampilkan error dari validator --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                  <label for="name" class="form-label">Nama Lengkap</label>
                  <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email" autocomplete="off" required/>
                </div>
                {{-- Input Nomor Telepon --}}
                <div class="mb-3">
                    <label for="telepon" class="form-label">Nomor Telepon</label>
                    <input
                      type="text"
                      class="form-control"
                      id="telepon"
                      name="telepon"
                      placeholder="Masukkan nomor telepon"
                      autofocus
                      required
                    />
                  </div>
                {{-- Input Alamat --}}
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Masukkan password" aria-describedby="password" required/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  <div class="invalid-feedback" id="passwordError">Password minimal 8 karakter</div>
                </div>

                <div class="mb-3">
                    <label for="tahun" class="form-label">Tahun Masuk</label>
                    <input
                      type="text"
                      class="form-control"
                      id="tahun"
                      name="tahunMasuk"
                      placeholder="Tahun Masuk Kerja"
                      required/>
                  </div>

                <div class="mb-3">
                    <label for="divisi" class="form-label">Divisi/Jabatan</label>
                    <select id="divisi" class="form-select @error('divisi') is-invalid @enderror" name="divisi" required>
                        <option value="" selected disabled>-- Pilih Divisi --</option>
                        <option value="manajemen" {{ old('divisi') == 'manajemen' ? 'selected' : '' }}>Manajemen</option>
                        <option value="produksi" {{ old('divisi') == 'produksi' ? 'selected' : '' }}>Produksi</option>
                        <option value="sales" {{ old('divisi') == 'sales' ? 'selected' : '' }}>Pemasaran & Penjualan</option>
                        <option value="desain" {{ old('divisi') == 'desain' ? 'selected' : '' }}>Desain Grafis</option>
                        <option value="keuangan" {{ old('divisi') == 'keuangan' ? 'selected' : '' }}>Keuangan & Administrasi</option>
                        <option value="logistik" {{ old('divisi') == 'logistik' ? 'selected' : '' }}>Logistik & Pengiriman</option>
                    </select>
                    @error('divisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="roleContainer" style="display: none;">
                    <label for="role" class="form-label">Jabatan</label>
                    <select id="role" class="form-select @error('role') is-invalid @enderror" name="role" required>
                        <option value="" selected disabled>-- Pilih Jabatan --</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="inputSales" style="display: none;">
                    <label for="inputSalesField" class="form-label">Brand</label>
                    <select id="inputSalesField" class="form-select @error('salesApa') is-invalid @enderror" name="salesApa">
                        <option value="" selected disabled>-- Pilih Brand --</option>
                        @foreach ($sales as $item)
                            <option value="{{ $item->id }}" {{ old('salesApa') == $item->id ? 'selected' : '' }}>
                                {{ $item->sales_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('salesApa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label">Tempat Kerja</label>
                    <select id="lokasi" class="form-select" name="lokasi" required>
                        <option selected disabled>-- Pilih Tempat Kerja --</option>
                        <option value="Malang">Malang</option>
                        <option value="Surabaya">Surabaya</option>
                        <option value="Kediri">Kediri</option>
                        <option value="Sidoarjo">Sidoarjo</option>
                      </select>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" value="1" required/>
                    <label class="form-check-label" for="terms-conditions">
                      Saya mematuhi
                      <a href="javascript:void(0);">syarat & ketentuan</a>
                    </label>
                  </div>
                </div>
                <button id="btnDaftar" type="submit" class="btn btn-primary d-grid w-100" disabled>Daftar</button>
              </form>

              <p class="text-center">
                <span>Sudah punya akun?</span>
                <a href="{{ route('auth.login') }}">
                  <span>Masuk</span>
                </a>
              </p>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('sneat') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('sneat') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script>
        $(document).ready(function() {
            const roles = {
                manajemen: [
                    {value: 'ceo', label: 'CEO'},
                    {value: 'direktur', label: 'Direktur'},
                    {value: 'manager', label: 'Manager'},
                    {value: 'hrd', label: 'HRD'}
                ],
                produksi: [
                    {value: 'estimator', label: 'Estimator / Supervisor / Kepala Cabang'},
                    {value: 'stempel', label: 'Staff Stempel'},
                    {value: 'advertising', label: 'Staff Advertising'},
                    {value: 'admin', label: 'Admin Workshop'},
                    {value: 'dokumentasi', label: 'Staff Dokumentasi'}
                ],
                sales: [
                    {value: 'supervisor', label: 'Supervisor'},
                    {value: 'sales', label: 'Sales'}
                ],
                desain: [
                    {value: 'supervisor', label: 'Supervisor'},
                    {value: 'staff', label: 'Staff Desain'}
                ],
                keuangan: [
                    {value: 'staffAdmin', label: 'Staff Admin'}
                ],
                logistik: [
                    {value: 'staffGudang', label: 'Staff Gudang / Logistik'}
                ]
            };

            function updateRoleOptions(selectedDivisi) {
                const $roleSelect = $('#role');
                const $roleContainer = $('#roleContainer');
                const $inputSales = $('#inputSales');
                
                $roleSelect.find('option:not(:first)').remove();
                
                if (!selectedDivisi) {
                    $roleContainer.hide();
                    $inputSales.hide();
                    return;
                }

                const divisiRoles = roles[selectedDivisi] || [];
                divisiRoles.forEach(role => {
                    $roleSelect.append(new Option(role.label, role.value));
                });

                if (selectedDivisi === 'sales') {
                    $inputSales.show();
                } else {
                    $inputSales.hide();
                }

                $roleContainer.show();
            }

            // Initial setup based on old input if exists
            const initialDivisi = $('#divisi').val();
            if (initialDivisi) {
                updateRoleOptions(initialDivisi);
                // Set old role value if exists
                const oldRole = "{{ old('role') }}";
                if (oldRole) {
                    $('#role').val(oldRole);
                }
            }

            // Update on division change
            $('#divisi').change(function() {
                updateRoleOptions($(this).val());
            });

            // Password validation
            $("#password").keyup(function() {
                var password = $(this).val();
                var passwordError = $("#passwordError");

                if (password.length < 8) {
                  passwordError.html("Password minimal 8 karakter");
                } else if (password.includes(" ")) {
                  passwordError.html("Password tidak boleh mengandung spasi");
                } else {
                  passwordError.html("");
                }
            });

            // Terms checkbox handling
            $("#terms-conditions").click(function() {
                if ($(this).is(":checked")) {
                $("#btnDaftar").removeAttr("disabled");
                } else {
                $("#btnDaftar").attr("disabled", true);
                }
            });
        });
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
