<!-- Workshop Queue -->
<li class="nav-item">
    <a href="{{ route('queue.workshop') }}" class="nav-link {{ Request::is('queue/workshop*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tools"></i>
        <p>Workshop Queue</p>
    </a>
</li>

<!-- My Work Orders -->
<li class="nav-item">
    <a href="{{ route('workshop.myorders') }}" class="nav-link {{ Request::is('workshop/myorders*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clipboard-list"></i>
        <p>My Work Orders</p>
    </a>
</li>

<!-- Machine Status -->
<li class="nav-item">
    <a href="{{ route('workshop.machines') }}" class="nav-link {{ Request::is('workshop/machines*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cogs"></i>
        <p>Machine Status</p>
    </a>
</li>

<!-- Production Reports -->
<li class="nav-item">
    <a href="{{ route('workshop.production') }}" class="nav-link {{ Request::is('workshop/production*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>Production Reports</p>
    </a>
</li>