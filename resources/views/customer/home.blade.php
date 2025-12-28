@extends('layouts.main')

@section('styles')
<style>
    /* === STYLE KUSTOM === */
    
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
        background-color: #fff;
    }

    .product-card:hover {
        transform: translateY(-8px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
    }
    
    /* Style untuk Info Bar */
    .info-box {
        border-right: 1px solid #eee;
    }
    .info-box:last-child {
        border-right: none;
    }
    @media (max-width: 768px) {
        .info-box {
            border-right: none;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .info-box:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
    }

    /* Style Sold Out Badge */
    .sold-out-badge {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 0.5rem 1rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        width: 100%;
        text-align: center;
    }
    .product-sold-out img {
        opacity: 0.6;
        filter: grayscale(100%);
    }
</style>
@endsection


@section('content')

{{-- === HERO SECTION === --}}
<div class="container text-center hero-section" style="position: relative;">
    <div>
        <h1 class="display-3">Gema Sandang</h1>
        <p class="lead">Pakaian 2<sup>nd</sup> berkualitas</p>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6"> 
                @include('layouts.partials.search-bar')
            </div>
        </div>

        {{-- ARROW BIRU SUDAH DIHAPUS --}}
    </div>
</div>

{{-- === INFO BAR (USP) === --}}
<div class="container mt-2 mb-5" id="info-penting">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-around text-center p-4 border rounded-4 shadow-sm bg-white">
                
                {{-- Info 1: Fitur Nego --}}
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-tags-fill text-warning fs-3 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1 text-uppercase" style="letter-spacing: 1px;">Bisa Nego</h6>
                    <small class="text-muted">Semua item boleh ditawar sesukanya!</small>
                </div>

                {{-- Info 2: Kondisi Barang --}}
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-stars text-primary fs-3 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1 text-uppercase" style="letter-spacing: 1px;">Siap Pakai</h6>
                    <small class="text-muted">Sudah dilaundry, wangi & higienis.</small>
                </div>

                {{-- Info 3: Keaslian --}}
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-gem text-danger fs-3 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1 text-uppercase" style="letter-spacing: 1px;">Vintage Asli</h6>
                    <small class="text-muted">Kurasi pilihan terbaik & eksklusif.</small>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- === KOLEKSI TERBARU === --}}
<div class="container my-5" id="koleksi-terbaru">
    <div class="row text-center mb-4">
        <div class="col">
            <h2 id="koleksi-terbaru-judul" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Koleksi Terbaru</h2>
            <p class="text-muted">Temukan gaya unik Anda dari koleksi pilihan kami.</p>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- End Alerts --}}
    
    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                {{-- Logika Sold Out: Tambah class jika stok 0 --}}
                <div class="card h-100 border-0 shadow-sm product-card {{ $product->stok == 0 ? 'product-sold-out' : '' }}">

                    {{-- Wrapper Gambar --}}
                    <div class="position-relative">
                        <a href="{{ route('product.show', $product) }}">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="height: 300px; object-fit: cover;">
                        </a>

                        {{-- Tampilkan Badge SOLD OUT jika stok 0 --}}
                        @if($product->stok == 0)
                            <div class="sold-out-badge">SOLD OUT</div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column p-3">
                        {{-- Kategori Kecil --}}
                        <div class="small text-muted mb-1">{{ $product->category->nama_kategori }}</div>

                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none" style="color: inherit;">
                            <h5 class="card-title text-truncate" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>
                        
                        {{-- HARGA SAJA (TANPA LABEL NEGO) --}}
                        <div class="mt-auto">
                            <h6 class="fw-bold mb-0" style="color: var(--primary-color); font-size: 1.1rem;">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </h6>
                            {{-- Info "Bisa Nego" sudah dihapus --}}
                        </div>
                        
                        <div class="mt-3 d-grid gap-2">
                            {{-- Tombol Detail --}}
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark btn-sm">Detail</a>
                            
                            {{-- Logika Tombol Add to Cart --}}
                            @if($product->stok > 0)
                                @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-custom btn-sm">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                                @elseguest
                                <a href="{{ route('login') }}" class="btn btn-custom btn-sm">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Buy
                                </a>
                                @endguest
                            @else
                                {{-- JIKA STOK 0 --}}
                                <button class="btn btn-secondary btn-sm" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center border-0 shadow-sm py-5">
                    <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
                    <p class="mb-0">Belum ada produk yang tersedia saat ini. Silakan kembali lagi nanti!</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row mt-4">
        <div class="col text-center">
            <a href="{{ route('shop') }}" class="btn btn-custom btn-lg px-5 shadow-sm rounded-pill">
                Lihat Semua Koleksi <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>

</div>

@endsection