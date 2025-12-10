@extends('layouts.main')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            {{-- Jika Bukti Belum Diupload --}}
            @if(!$order->bukti_bayar)
                <div class="text-center mb-5">
                    <i class="bi bi-hourglass-split" style="font-size: 4rem; color: var(--secondary-color);"></i>
                    <h2 class="mt-3" style="font-family: 'Playfair Display', serif;">Menunggu Pembayaran</h2>
                    <p class="text-muted">Pesanan #{{ $order->id }} berhasil dibuat. Silakan selesaikan pembayaran.</p>
                </div>

                {{-- Info Rekening --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3 text-center">Transfer Manual ke:</h5>
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-2">
                            <div>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" height="20">
                                <div class="small text-muted mt-1">Gema Sandang Official</div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 fw-bold">123-456-7890</h5>
                                <small class="text-muted" style="cursor: pointer;">Salin</small>
                            </div>
                        </div>
                        <div class="alert alert-warning small mb-0 text-center">
                            Total Bayar: <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Form Upload Bukti --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Kirim Bukti Transfer</h5>
                        <form action="{{ route('checkout.payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small">Upload Foto Bukti (JPG/PNG)</label>
                                <input type="file" name="bukti_bayar" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-custom w-100">
                                Kirim Bukti Pembayaran <i class="bi bi-send-fill ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>

            {{-- Jika Bukti SUDAH Diupload --}}
            @else
                <div class="text-center mb-5">
                    <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: green;"></i>
                    <h2 class="mt-3" style="font-family: 'Playfair Display', serif;">Bukti Diterima!</h2>
                    <p class="text-muted">Terima kasih. Admin kami sedang memverifikasi pembayaran Anda.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('shop') }}" class="btn btn-outline-dark px-4">Belanja Lagi</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-custom px-4">Lihat Pesanan Saya</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection