<!-- Orders -->
<li class="nav-item">
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('antrian*') && !Request::is('antrian/search') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Antrian</p>
    </a>
</li>

{{-- Cari Order Berdasarkan Tiket --}}
<li class="nav-item">
    <a href="{{ route('antrian.cariOrder') }}" class="nav-link {{ Request::is('antrian/search') ? 'active' : '' }}">
        <i class="nav-icon fas fa-search"></i>
        <p>Cari Order</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('design.index') }}" class="nav-link {{ Request::is('order*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-pen"></i>
        <p>Desain</p>
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
        <p>Penawaran</p>
    </a>
</li>

<!-- Customers -->
<li class="nav-item">
    <a href="{{ route('customer.index') }}" class="nav-link {{ Request::is('master/customer*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>Pelanggan</p>
    </a>
</li>

<!-- My Performance -->
{{-- <li class="nav-item">
    <a href="{{ route('report.mysales') }}" class="nav-link {{ Request::is('report/mysales*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>My Performance</p>
    </a>
</li> --}}