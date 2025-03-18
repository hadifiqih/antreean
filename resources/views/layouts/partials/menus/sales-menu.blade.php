<!-- Orders -->
<li class="nav-item">
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('order*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Antrian</p>
    </a>
</li>

<!-- Daily Report -->
<li class="nav-item">
    <a href="{{ route('sales.reports.index') }}" class="nav-link {{ Request::is('report/daily*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-day"></i>
        <p>Laporan Harian</p>
    </a>
</li>

<!-- Offers -->
<li class="nav-item">
    <a href="{{ route('sales.offers.index') }}" class="nav-link {{ Request::is('sales/offers*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-hand-holding-usd"></i>
        <p>Offers</p>
    </a>
</li>

<!-- Customers -->
<li class="nav-item">
    <a href="{{ route('customer.index') }}" class="nav-link {{ Request::is('master/customer*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Customers</p>
    </a>
</li>

<!-- My Performance -->
{{-- <li class="nav-item">
    <a href="{{ route('report.mysales') }}" class="nav-link {{ Request::is('report/mysales*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>My Performance</p>
    </a>
</li> --}}