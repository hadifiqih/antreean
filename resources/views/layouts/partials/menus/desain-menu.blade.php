<!-- Design Queue -->
<li class="nav-item">
    <a href="{{ route('design.index') }}" class="nav-link {{ Request::is('design*') || Request::is('design*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-pen"></i>
        <p>Desain</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('antrian*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Antrian</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('task.index') }}" class="nav-link {{ Request::is('task*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tasks"></i>
        <p>Tugasku</p>
    </a>
</li>