@extends('layouts.main')

@section('content')

<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                <img src="{{ asset('storage/' . $product->foto_produk) }}" class="card-img-top" alt="{{ $product->nama_produk }}" style="border-radius: 0.75rem;">
            </div>
        </div>

        <div class="col-lg-6">
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
                <div class="d-grid gap-3">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-custom btn-lg w-100 shadow-sm" {{ $product->stok == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus"></i> Tambahkan ke Keranjang
                        </button>
                    </form>

                    @if($product->stok > 0)
                        <button type="button" class="btn btn-outline-dark btn-lg w-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#bargainModal">
                            <i class="bi bi-tags"></i> Tawar Harga
                        </button>
                    @endif
                </div>

                <div class="modal fade" id="bargainModal" tabindex="-1" aria-labelledby="bargainModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0 bg-light">
                                <h5 class="modal-title" id="bargainModalLabel" style="font-family: 'Playfair Display', serif;">Ajukan Penawaran Harga</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('bargains.store') }}" method="POST">
                                @csrf
                                <div class="modal-body p-4">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    <div class="text-center mb-4">
                                        <p class="text-muted small mb-1">Harga Asli:</p>
                                        <h4 class="fw-bold text-dark">Rp {{ number_format($product->harga, 0, ',', '.') }}</h4>
                                    </div>

                                    <div class="mb-3">
                                        <label for="harga_tawaran" class="form-label small text-muted fw-bold">Harga yang Anda Inginkan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">Rp</span>
                                            
                                            <input type="number" 
                                                   name="harga_tawaran" 
                                                   id="harga_tawaran" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Minimal Rp {{ number_format($product->harga * 0.5, 0, ',', '.') }}" 
                                                   value="{{ old('harga_tawaran', request('last_offer')) }}" 
                                                   min="{{ $product->harga * 0.5 }}" 
                                                   max="{{ $product->harga - 1 }}"
                                                   required>
                                        </div>
                                        <small class="text-danger mt-2 d-block">
                                            *Minimal tawaran: Rp {{ number_format($product->harga * 0.5, 0, ',', '.') }} (50% harga asli)
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-3">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-custom px-4">Kirim Penawaran</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @elseguest
                <a href="{{ route('login') }}" class="btn btn-custom btn-lg w-100 shadow-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli / Menawar
                </a>
            @endguest

        </div>
    </div>
</div>

@endsection