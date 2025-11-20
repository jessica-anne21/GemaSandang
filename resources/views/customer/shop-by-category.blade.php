@extends('layouts.main')

{{-- Menambahkan CSS kustom untuk efek hover kartu --}}
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

<div class="row justify-content-center mb-4">
        <div class="col-md-6">
             @include('layouts.partials.search-bar')
        </div>
    </div>
    
    <div class="row text-center mb-5"> 
        <div class="col">
            <p class="text-muted mb-2">Menampilkan produk untuk kategori:</p>
            <h1 class="display-4" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                {{ $category->nama_kategori }}
            </h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">

                    <a href="{{ route('product.show', $product) }}">
                        <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="height: 300px; object-fit: cover;">
                    </a>

                    <div class="card-body d-flex flex-column">

                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none" style="color: inherit;">
                            <h5 class="card-title" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h5>
                        </a>

                        {{-- <p class="card-text text-muted">{{ $product->category->nama_kategori }}</p> --}} 
                        
                        <p class="card-text fw-bold mt-auto" style="color: var(--primary-color);">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        
                        <div class="mt-3 d-grid gap-2"> 
                            <a href="{{ route('product.show', $product) }}" class="btn btn-outline-dark">Detail</a>
                            
                            @auth
                            {{-- Jika sudah login, tampilkan form Add to Cart --}}
                            <div class> 
                                <form action="{{ route('cart.store') }}" method="POST" class="d-grid">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-custom">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                            @elseguest
                            {{-- Jika masih guest, tampilkan tombol menuju Login --}}
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
                <div class="alert alert-warning text-center">
                    <p class="mb-0">Belum ada produk yang tersedia untuk kategori ini.</p>
                </div>
            </div>
        @endforelse 
    </div>
</div>

@endsection