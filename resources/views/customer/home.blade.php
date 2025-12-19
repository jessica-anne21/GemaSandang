@extends('layouts.main')

@section('styles')
<style>
    /* Style kustom yang Anda berikan sebelumnya */
    .scroll-down-link {
        color: var(--primary-color); 
        font-size: 2.5rem; 
        animation: bounce 2s infinite; 
        display: inline-block;
        margin-top: 1.5rem;
        transition: color 0.3s ease;
    }
    
    .scroll-down-link:hover {
        color: var(--primary-hover); 
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-15px); 
        }
        60% {
            transform: translateY(-10px);
        }
    }

    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
    }

    .product-card:hover {
        transform: translateY(-8px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
    }
    
    /* (Pastikan CSS Sold Out di-load dari app.css atau masukkan di sini jika tidak menggunakan file CSS eksternal) */
    /* Contoh jika dimasukkan di sini:
    .product-sold-out { opacity: 0.6; filter: grayscale(100%); position: relative; }
    .sold-out-badge { ... }
    */
</style>
@endsection


@section('content')

<div class="container text-center hero-section" style="position: relative;">
    <div>
        <h1 class="display-3">Gema Sandang</h1>
        <p class="lead">Pakaian 2<sup>nd</sup> berkualitas</p>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6"> 
                @include('layouts.partials.search-bar')
            </div>
        </div>

        <div>
            <a href="#koleksi-terbaru" class="scroll-down-link" aria-label="Lihat koleksi">
                <i class="bi bi-chevron-down"></i>
            </a>
        </div>
    </div>
</div>

<div class="container my-5" id="koleksi-terbaru">
    <div class="row text-center mb-4">
        <div class="col">
            <h2 id="koleksi-terbaru-judul" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Koleksi Terbaru</h2>
            <p class="text-muted">Temukan gaya unik Anda dari koleksi pilihan kami.</p>
        </div>
    </div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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

                    <div class="card-body d-flex flex-column">
                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none" style="color: inherit;">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>
                        <p class="card-text text-muted">{{ $product->category->nama_kategori }}</p>
                        <p class="card-text fw-bold mt-auto" style="color: var(--primary-color);">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        
                        <div class="mt-3 d-grid gap-2">
                            {{-- Tombol Detail: Tetap aktif --}}
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark">Detail</a>
                            
                            {{-- Logika Tombol Add to Cart --}}
                            @if($product->stok > 0)
                                @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-custom">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                                @elseguest
                                <a href="{{ route('login') }}" class="btn btn-custom">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Buy
                                </a>
                                @endguest
                            @else
                                {{-- JIKA STOK 0, TAMPILKAN TOMBOL SOLD OUT MATI --}}
                                <button class="btn btn-secondary" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <p class="mb-0">Belum ada produk yang tersedia saat ini. Silakan kembali lagi nanti!</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row mt-4">
        <div class="col text-center">
            <a href="{{ route('shop') }}" class="btn btn-custom btn-lg">
                Lihat Semua Koleksi
                <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>

</div>

@endsection