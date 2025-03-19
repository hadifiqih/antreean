<!-- Design Queue -->
<li class="nav-item">
    <a href="{{ route('design.index') }}" class="nav-link {{ Request::is('order*') || Request::is('design*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-pen"></i>
        <p>Desain</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('antrian.index') }}" class="nav-link {{ Request::is('order*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Antrian</p>
    </a>
</li>