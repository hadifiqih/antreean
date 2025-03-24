<li class="nav-item {{ Request::is('payments*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('payments*') ? 'active' : '' }}">
        <i class="nav-icon fas fas fa-shopping-cart"></i>
        <p>
            Pembayaran
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('payments.index') }}" class="nav-link {{ Request::is('payments*') ? 'active' : '' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Piutang</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payments.validation') }}" class="nav-link {{ Request::is('validasi-pembayaran*') ? 'active' : '' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Pengecekan</p>
            </a>
        </li>
    </ul>
</li>