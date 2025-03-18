<!-- Dashboard -->
<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

@if(Auth::user()->role == 'admin')
    @include('layouts.partials.menus.admin-menu')
@elseif(Auth::user()->role == 'sales')
    @include('layouts.partials.menus.sales-menu')
@elseif(Auth::user()->role == 'supervisor')
    @include('layouts.partials.menus.supervisor-menu')
@elseif(Auth::user()->role == 'desain')
    @include('layouts.partials.menus.desain-menu')
@elseif(Auth::user()->role == 'operator')
    @include('layouts.partials.menus.operator-menu')
@endif

<!-- Logout -->
<li class="nav-item">
    <a href="#" class="nav-link" onclick="confirmLogout()">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>