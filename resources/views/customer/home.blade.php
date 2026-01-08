@extends('layouts.main')

@section('styles')
<style>     
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
        background-color: #fff;
    }
    .product-card:hover {
        transform: translateY(-8px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
    }
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
    .btn-custom {
        background-color: var(--primary-color);
        color: white;
    }
    .btn-custom:hover {
        color: white;
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="container text-center hero-section py-5" style="position: relative;">
    <div>
        <h1 class="display-3" style="font-family: 'Playfair Display', serif;">Gema Sandang</h1>
        <p class="lead">Pakaian 2<sup>nd</sup> berkualitas</p>
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6"> 
                @include('layouts.partials.search-bar')
            </div>
        </div>
    </div>
</div>

<div class="container mt-2 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-around text-center p-4 border rounded-4 shadow-sm bg-white">
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-tags-fill text-warning fs-3 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1 text-uppercase">Bisa Nego</h6>
                    <small class="text-muted">Semua item boleh ditawar sesukanya!</small>
                </div>
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-stars text-primary fs-3 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1 text-uppercase">Siap Pakai</h6>
                    <small class="text-muted">Sudah dilaundry, wangi & higienis.</small>
                </div>
                <div class="info-box px-3 flex-fill">
                    <i class="bi bi-gem text-danger fs-3 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1 text-uppercase">Vintage Asli</h6>
                    <small class="text-muted">Kurasi pilihan terbaik & eksklusif.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center mb-4">
        <div class="col">
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Koleksi Terbaru</h2>
            <p class="text-muted">Temukan gaya unik Anda dari koleksi pilihan kami.</p>
        </div>
    </div>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card {{ $product->stok == 0 ? 'product-sold-out' : '' }}">
                    <div class="position-relative">
                        <a href="{{ route('product.show', $product) }}">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->nama_produk }}" 
                                 style="height: 300px; object-fit: cover;">
                        </a>
                        @if($product->stok == 0)
                            <div class="sold-out-badge">SOLD OUT</div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <div class="small text-muted mb-1">{{ $product->category->nama_kategori }}</div>
                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none" style="color: inherit;">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>
                        <div class="mt-auto">
                            <h6 class="fw-bold mb-0" style="color: var(--primary-color); font-size: 1.1rem;">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </h6>
                        </div>
                        <div class="mt-3 d-grid gap-2">
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark btn-sm">Detail</a>
                            @if($product->stok > 0)
                                @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-custom btn-sm">
                                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                                @elseguest
                                <a href="{{ route('login') }}" class="btn btn-custom btn-sm">
                                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Beli
                                </a>
                                @endguest
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-5">
                    <p class="mb-0">Belum ada produk tersedia.</p>
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