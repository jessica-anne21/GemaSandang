@extends('layouts.main')

@section('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection

@section('content')

<div class="container my-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
             @include('layouts.partials.search-bar')
        </div>
    </div>

    <div class="row text-center mb-5">
        <div class="col">
            @if(isset($query) && $query != null)
                <p class="text-muted mb-2">Hasil Pencarian:</p>
                <h2 style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                    "{{ $query }}"
                </h2>
                <p class="text-muted mt-2">Ditemukan {{ $products->count() }} produk</p>
                <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-x-circle"></i> Reset Pencarian
                </a>
            @else
                <h1 class="display-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                    Koleksi Kami
                </h1>
                <p class="lead text-muted">Temukan gaya unik Anda dari koleksi pilihan kami.</p>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach (['success', 'warning', 'error'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show shadow-sm" role="alert">
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">

                    <a href="{{ route('product.show', $product) }}">
                        <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="height: 300px; object-fit: cover;">
                    </a>

                    <div class="card-body d-flex flex-column">
                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none text-dark">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>
                        
                        <p class="card-text text-muted small mb-2">{{ $product->category->nama_kategori }}</p>
                        
                        <p class="card-text fw-bold mt-auto fs-5" style="color: var(--primary-color);">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        
                        <div class="mt-3 d-grid gap-2">
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark">Detail</a>
                            
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
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center py-5">
                    <i class="bi bi-search display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Produk tidak ditemukan.</h4>
                    <p class="text-muted">Coba kata kunci lain atau kembali lagi nanti.</p>
                    @if(isset($query))
                         <a href="{{ route('shop') }}" class="btn btn-outline-dark mt-3">Lihat Semua Produk</a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection