<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-danger navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                @include('layouts.partials.notifications')
                <a href="{{ route('notification.markAllAsRead') }}" class="dropdown-item dropdown-footer {{ auth()->user()->unreadNotifications->count() > 0 ? "" : "disabled" }}">Tandai sudah dibaca ({{ auth()->user()->unreadNotifications->count() }})</a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->