<!-- Orders -->
<li class="nav-item {{ Request::is('antrian*') || Request::is('antrian/search') || Request::is('payments*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('antrian*') || Request::is('antrian/search') || Request::is('payments*') ? 'active' : '' }}">
      <i class="nav-icon fas fas fa-shopping-cart"></i>
      <p>
        Orderan
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('antrian*') && !Request::is('antrian/search') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Antrian</p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ route('payments.index') }}" class="nav-link {{ Request::is('payments*') ? 'active' : '' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Pembayaran</p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('antrian.cariOrder') }}" class="nav-link {{ Request::is('antrian/search') ? 'active' : '' }}">
                <i class="nav-icon far fa-circle"></i>
                <p>Cari Order</p>
            </a>
        </li>
    </ul>
</li>

{{-- Cari Order Berdasarkan Tiket --}}
<li class="nav-item">
    <a href="{{ route('design.index') }}" class="nav-link {{ Request::is('design*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-pen"></i>
        <p>Desain</p>
    </a>
</li>

<!-- Daily Report -->
<li class="nav-item">
    <a href="{{ route('sales.reports.index') }}" class="nav-link {{ Request::is('report/daily*') || Request::is('sales/reports*') ? 'active' : '' }}">
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
