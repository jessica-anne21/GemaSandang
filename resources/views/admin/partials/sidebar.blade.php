<div class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar-custom" style="width: 280px;">    
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Admin Panel</span>
    </a>
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i>
                Kelola Produk
            </a>
        </li>
        <li>
            <a href="{{ route('admin.categories.index') }}" class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i>
                Kelola Kategori
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-card-checklist me-2"></i>
                Kelola Pesanan
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white"> 
                <i class="bi bi-people me-2"></i>
                Pelanggan
            </a>
        </li>
    </ul>
    
    {{-- Menu Logout --}}
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i>
            <strong>{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser1">
            <li>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Keluar</button>
            </form>
        </ul>
    </div>
</div>