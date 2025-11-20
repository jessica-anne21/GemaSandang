@extends('layouts.main')

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="border-radius: 0.75rem;">
            </div>
        </div>

        <div class="col-lg-6">
            {{-- Kategori --}}
            <span class="badge" style="background-color: var(--secondary-color, #C1A77E); color: var(--primary-color, #8D4B55); font-size: 0.9rem;">
                {{ $product->category->nama_kategori }}
            </span>

            <h1 class="display-5 mt-2" style="font-family: 'Playfair Display', serif;">{{ $product->nama_produk }}</h1>
            
            <h2 class="h3 my-3" style="color: var(--primary-color); font-weight: 700;">
                Rp {{ number_format($product->harga, 0, ',', '.') }}
            </h2>

            <p class="text-muted">Stok Tersedia: <strong>{{ $product->stok }}</strong></p>

            <hr class="my-4">

            <h5 style="font-family: 'Playfair Display', serif;">Deskripsi Produk</h5>
            <p style="white-space: pre-wrap;">{{ $product->deskripsi }}</p>

            <hr class="my-4">

            @auth
                {{-- JIKA SUDAH LOGIN, TAMPILKAN FORM ADD TO CART --}}
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-custom btn-lg w-100">
                        <i class="bi bi-cart-plus"></i> Tambahkan ke Keranjang
                    </button>
                </form>
            @elseguest
                {{-- JIKA MASIH GUEST, TAMPILKAN TOMBOL MENUJU LOGIN --}}
                <a href="{{ route('login') }}" class="btn btn-custom btn-lg w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                </a>
            @endguest

        </div>
    </div>
</div>

@endsection