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
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('order*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Antrian</p>
    </a>
</li>