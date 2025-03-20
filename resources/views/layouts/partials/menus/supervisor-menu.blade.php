{{-- <!-- Orders Overview -->
<li class="nav-item">
    <a href="{{ route('order.index') }}" class="nav-link {{ Request::is('order*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tasks"></i>
        <p>Orders Overview</p>
    </a>
</li>

<!-- Queue Management -->
<li class="nav-item {{ Request::is('queue*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('queue*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clock"></i>
        <p>
            Queue Management
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('queue.workshop') }}" class="nav-link {{ Request::is('queue/workshop*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Workshop Queue</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('queue.design') }}" class="nav-link {{ Request::is('queue/design*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Design Queue</p>
            </a>
        </li>
    </ul>
</li>

<!-- Performance Reports -->
<li class="nav-item {{ Request::is('report*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('report*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-bar"></i>
        <p>
            Performance Reports
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('report.team') }}" class="nav-link {{ Request::is('report/team*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Team Performance</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('report.efficiency') }}" class="nav-link {{ Request::is('report/efficiency*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Efficiency Metrics</p>
            </a>
        </li>
    </ul>
</li> --}}
<!-- Orders -->
<li class="nav-item">
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('antrian*') ? 'active' : '' }}">
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