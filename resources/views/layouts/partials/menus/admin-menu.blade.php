<!-- Master Data -->
<li class="nav-item {{ Request::is('master*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('master*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-database"></i>
        <p>
            Master Data
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('customer.index') }}" class="nav-link {{ Request::is('master/customer*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Customer</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('employee.index') }}" class="nav-link {{ Request::is('master/employee*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Employee</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('machine.index') }}" class="nav-link {{ Request::is('master/machine*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Machine</p>
            </a>
        </li>
    </ul>
</li>

<!-- Orders -->
<li class="nav-item">
    <a href="{{ route('order.index') }}" class="nav-link {{ Request::is('order*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p>Orders</p>
    </a>
</li>

<!-- Reports -->
<li class="nav-item {{ Request::is('report*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('report*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-bar"></i>
        <p>
            Reports
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('report.sales') }}" class="nav-link {{ Request::is('report/sales*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Report</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('report.performance') }}" class="nav-link {{ Request::is('report/performance*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Performance Report</p>
            </a>
        </li>
    </ul>
</li>