@extends('layouts.main')

@section('content')
<div class="container my-5">
    
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5" style="font-family: 'Playfair Display', serif;">Checkout</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>Ups! Ada masalah dengan input Anda:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm p-4">
                    <h4 class="mb-4" style="color: var(--primary-color); font-family: 'Playfair Display', serif;">Detail Pengiriman</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nama Penerima</label>
                            <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nomor WhatsApp / HP</label>
                            <input type="text" name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" 
                                   value="{{ old('nomor_hp', Auth::user()->nomor_hp) }}" placeholder="Contoh: 0812..." required>
                            
                            @error('nomor_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_pengiriman" class="form-control @error('alamat_pengiriman') is-invalid @enderror" 
                                  rows="4" placeholder="Nama Jalan, No Rumah, Kecamatan, Kota, Kode Pos" required>{{ old('alamat_pengiriman', Auth::user()->alamat) }}</textarea>
                        @error('alamat_pengiriman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-4" style="color: var(--primary-color); font-family: 'Playfair Display', serif;">Opsi Pengiriman</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Pilih Kurir</label>
                            <select name="kurir" class="form-select @error('kurir') is-invalid @enderror" required id="kurirSelect">
                                <option value="" disabled selected>Pilih Kurir</option>
                                <option value="jne" {{ old('kurir') == 'jne' ? 'selected' : '' }}>JNE (Regular)</option>
                                <option value="jnt" {{ old('kurir') == 'jnt' ? 'selected' : '' }}>J&T Express</option>
                                <option value="sicepat" {{ old('kurir') == 'sicepat' ? 'selected' : '' }}>SiCepat</option>
                                <option value="grab" {{ old('kurir') == 'grab' ? 'selected' : '' }}>GrabExpress (Instant)</option>
                            </select>
                            @error('kurir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Biaya Ongkir</label>
                            <select name="ongkir" class="form-select @error('ongkir') is-invalid @enderror" id="ongkirSelect" required>
                                <option value="" selected disabled>Pilih lokasi tujuan</option>
                                <option value="20000" {{ old('ongkir') == '20000' ? 'selected' : '' }}>Bandung - Rp 20.000</option>
                                <option value="35000" {{ old('ongkir') == '35000' ? 'selected' : '' }}>Luar Bandung - Rp 35.000</option>
                            </select>
                            @error('ongkir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Catatan (Opsional)</label>
                        <textarea name="catatan_customer" class="form-control" rows="2" placeholder="Contoh: Tolong packing yang aman ya">{{ old('catatan_customer') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4" style="background-color: #fafafa; border-radius: 0.75rem;">
                    <h4 class="mb-4" style="color: var(--primary-color); font-family: 'Playfair Display', serif;">Ringkasan Pesanan</h4>
                    
                    {{-- Daftar Item --}}
                    <div class="checkout-items mb-4 pe-2" style="max-height: 300px; overflow-y: auto;">
                        @php $subtotal = 0; @endphp
                        
                        @foreach($cart as $details)
                            @php 
                                $itemTotal = $details['price'] * $details['quantity'];
                                $subtotal += $itemTotal; 
                            @endphp
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $details['photo']) }}" class="rounded border" width="60" height="60" style="object-fit: cover;">                                    <div class="ms-3">
                                        <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ $details['name'] }}</h6>
                                        <small class="text-muted">@ Rp {{ number_format($details['price'], 0, ',', '.') }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold text-end">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total Perhitungan --}}
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="text-success" id="ongkirDisplay">Rp 0 (Pilih Kurir)</span>
                    </div>

                    <hr class="border-2">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h5 mb-0">Total Pembayaran</span>
                        <span class="h4 mb-0 fw-bold" style="color: var(--primary-color);" id="totalDisplay">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <button type="submit" class="btn btn-custom w-100 py-3 fs-5 shadow-sm">
                        Buat Pesanan <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('cart.index') }}" class="text-muted text-decoration-none small hover-underline">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.getElementById('ongkirSelect').addEventListener('change', function() {
        let ongkir = parseInt(this.value);
        if (isNaN(ongkir)) ongkir = 0;

        let subtotal = {{ $subtotal }};
        let total = subtotal + ongkir;
        
        document.getElementById('ongkirDisplay').innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
        document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>

@endsection