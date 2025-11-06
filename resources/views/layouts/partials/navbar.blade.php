<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">Gema Sandang</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('kategori/*') ? 'active' : '' }}" href="#" id="navbarDropdownKategori" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Kategori
            </a>
            <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDropdownKategori">
                @foreach ($categories as $category)
                    <li>
                        <a class="dropdown-item" href="{{ route('category.show', $category->id) }}">
                            {{ $category->nama_kategori }}
                        </a>
                    </li>
                @endforeach

                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('shop') }}">Lihat Semua Produk</a>
                </li>
            </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Tentang</a></li>
      </ul>

      <div class="d-flex align-items-center">
        <a href="{{ route('cart.index') }}" class="nav-link me-3"><i class="bi bi-cart me-1"></i> Keranjang</a>
        @auth
            <div class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            Keluar
                        </a>
                    </form>
                </li>
              </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="nav-link"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
        @endauth
      </div>
    </div>
  </div>
</nav>