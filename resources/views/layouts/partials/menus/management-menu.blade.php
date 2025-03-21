<!-- Orders -->
<li class="nav-item">
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('antrian*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Antrian</p> 
    </a>
</li>

<!-- Daily Report -->
<li class="nav-item">
    <a href="{{ route('sales.reports.index') }}" class="nav-link {{ Request::is('report/daily*') || Request::is('sales/reports*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-day"></i>
        <p>Laporan Harian</p>
    </a>
</li>

<!-- Customers -->
<li class="nav-item">
    <a href="{{ route('customer.index') }}" class="nav-link {{ Request::is('master/customer*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Pelanggan</p>
    </a>
</li>