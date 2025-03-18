<!-- Design Queue -->
<li class="nav-item">
    <a href="{{ route('queue.design') }}" class="nav-link {{ Request::is('queue/design*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-paint-brush"></i>
        <p>Design Queue</p>
    </a>
</li>

<!-- My Tasks -->
<li class="nav-item">
    <a href="{{ route('design.mytasks') }}" class="nav-link {{ Request::is('design/mytasks*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-pencil-ruler"></i>
        <p>My Tasks</p>
    </a>
</li>

<!-- Design Archive -->
<li class="nav-item">
    <a href="{{ route('design.archive') }}" class="nav-link {{ Request::is('design/archive*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-archive"></i>
        <p>Design Archive</p>
    </a>
</li>

<!-- Performance -->
<li class="nav-item">
    <a href="{{ route('design.performance') }}" class="nav-link {{ Request::is('design/performance*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>My Performance</p>
    </a>
</li>