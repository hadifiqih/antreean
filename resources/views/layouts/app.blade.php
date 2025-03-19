<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="icon" href="{{ url('adminlte/dist/img/antree-logo.png') }}" type="image/png" sizes="16x16">
  <title>Software Antree | Kassab Syariah</title>
  @vite('resources/js/app.js')
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Google Font: Inter -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ url('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('adminlte/dist/css/adminlte.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ url('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  {{-- Dropzone --}}
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

  <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>

  <style>
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .loader {
        border: 3px solid #f3f3f3; /* Light grey */
        border-top: 3px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 0.3s linear infinite;
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>

  @yield('style')
  @stack('styles')
  {{-- Pusher --}}
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
          .then(registration => {
            console.log('SW registered: ', registration);
          })
          .catch(registrationError => {
            console.log('SW registration failed: ', registrationError);
          });
      });
    }
  </script>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('layouts.partials.header')
    @include('layouts.partials.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('page')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @php
                $segments = Request::segments();
                $url = '';
              @endphp
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
              @foreach($segments as $segment)
                @php
                  $url .= '/'.$segment;
                  $segmentName = ucfirst(str_replace('-', ' ', $segment));
                @endphp
                @if($loop->last)
                  <li class="breadcrumb-item active">{{ $segmentName }}</li>
                @else
                  <li class="breadcrumb-item"><a href="{{ url($url) }}">{{ $segmentName }}</a></li>
                @endif
              @endforeach
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content p-4">
      @yield('content')
    </section>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  @include('layouts.partials.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ url('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ url('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@yield('script')
{{-- Select2 --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

{{-- BS-Custom-Input-File --}}
<script src="{{ url('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<!-- AdminLTE -->
<script src="{{ url('adminlte/dist/js/adminlte.js') }}"></script>

{{-- DayJS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.7/dayjs.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script>

    function notif(data) {
        if(data.message.title == 'Antrian Workshop') {
            $(document).Toasts('create', {
            class: 'bg-warning',
            body: data.message.body,
            title: data.message.title,
            icon: 'fas fa-envelope fa-lg',
            });
        }else if(data.message.title == 'Antrian Desain') {
            $(document).Toasts('create', {
            class: 'bg-info',
            body: data.message.body,
            title: data.message.title,
            icon: 'fas fa-envelope fa-lg',
            });
        }

    };
</script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });

    function confirmLogout(){
        const confirmation = confirm('Apakah Anda yakin ingin keluar?');
        if (confirmation) {
          // Redirect to the logout URL if the user clicks "OK"
          window.location.href = "{{ route('auth.logout') }}";
        }
    }

</script>
<script>
    function sendReminder() {
            $.ajax({
                type: "GET",
                url: "{{ route('antrian.reminder') }}",
                success: function (response) {
                    console.log(response);
                }
            })
        }
    $(document).ready(function() {
        var targetWaktu = '16:45'

        var interval = 60000;

        function checkTime(){
            var waktuSekarang = dayjs().format('HH:mm');
            if(waktuSekarang == targetWaktu){
                sendReminder();
            }
        }

        setInterval(checkTime, interval);
    });
</script>
@stack('scripts')

</body>
</html>
