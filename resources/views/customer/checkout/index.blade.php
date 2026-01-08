@extends('layouts.main')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5" style="font-family: 'Playfair Display', serif;">Checkout</h1>
        </div>
    </div>

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
                            <label class="form-label text-muted small text-uppercase fw-bold">Nomor WhatsApp</label>
                            <input type="text" name="nomor_hp" class="form-control" value="{{ Auth::user()->nomor_hp }}" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_pengiriman" class="form-control" rows="4" required>{{ Auth::user()->alamat }}</textarea>
                    </div>

                    <hr class="my-4">
                    <h4 class="mb-4" style="color: var(--primary-color); font-family: 'Playfair Display', serif;">Opsi Pengiriman</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Pilih Kurir</label>
                            <select name="kurir" class="form-select" required>
                                <option value="jne">JNE (Regular)</option>
                                <option value="jnt">J&T Express</option>
                                <option value="sicepat">SiCepat</option>
                                <option value="grab">GrabExpress</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Biaya Ongkir</label>
                            <select name="ongkir" class="form-select" id="ongkirSelect" required>
                                <option value="" disabled selected>Pilih Lokasi</option>
                                <option value="20000">Bandung - Rp 20.000</option>
                                <option value="35000">Luar Bandung - Rp 35.000</option>
                            </select>
                        </div>
                    </div>

                    {{-- CATATAN PELANGGAN DI BAWAH OPSI PENGIRIMAN --}}
                    <div class="mt-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Catatan Pesanan (Opsional)</label>
                        <textarea name="catatan_customer" class="form-control" rows="2" placeholder="Contoh: Titip di satpam atau warna plastik bungkusnya ya kak..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4" style="background-color: #fafafa;">
                    <h4 class="mb-4" style="color: var(--primary-color); font-family: 'Playfair Display', serif;">Ringkasan Pesanan</h4>
                    <div class="checkout-items mb-4" style="max-height: 300px; overflow-y: auto;">
                        @php $subtotal = 0; @endphp
                        @foreach($cartItems as $item)
                            @php 
                                $itemTotal = $item->price * $item->quantity;
                                $subtotal += $itemTotal; 
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $item->product->foto_produk) }}" class="rounded border" width="60" height="60" style="object-fit: cover;">
                                    <div class="ms-3">
                                        <h6 class="mb-0">{{ $item->product->nama_produk }}</h6>
                                        <small class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                        @if($item->is_bargain)
                                            <br><span class="badge bg-success" style="font-size: 0.6rem;">Harga Nego</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Ongkir</span>
                        <span id="ongkirDisplay">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h5">Total</span>
                        <span class="h4 fw-bold" id="totalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" class="btn btn-custom w-100 py-3 fs-5">Buat Pesanan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('ongkirSelect').addEventListener('change', function() {
        let ongkir = parseInt(this.value);
        let subtotal = {{ $subtotal }};
        let total = subtotal + ongkir;
        document.getElementById('ongkirDisplay').innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
        document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
@endsection