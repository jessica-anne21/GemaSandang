@extends('layouts.main')

@section('content')
<div class="container my-5 py-5">
    
    {{-- Hitung sisa waktu di Server (PHP) agar akurat --}}
    @php
        // Waktu kadaluwarsa (1 menit dari waktu pesanan dibuat)
        // Ganti '1' dengan '24 * 60' jika nanti sudah production (24 jam)
        $limitInMinutes = 1; 
        $expiryTime = $order->created_at->addMinutes($limitInMinutes);
        // Hitung sisa detik (bisa negatif jika sudah lewat)
        $remainingSeconds = now()->diffInSeconds($expiryTime, false);
    @endphp

    {{-- KONDISI 1: Menunggu Pembayaran & Waktu Masih Ada --}}
    @if($order->status == 'menunggu_pembayaran' && !$order->bukti_bayar)
        
        {{-- Jika waktu di server sudah habis (sebelum halaman dimuat) --}}
        @if($remainingSeconds <= 0)
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-sm p-5">
                        <div class="mb-3">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="mb-3" style="font-family: 'Playfair Display', serif;">Waktu Habis</h2>
                        <p class="text-muted mb-4">
                            Maaf, batas waktu pembayaran untuk pesanan #{{ $order->id }} telah berakhir.
                            Silakan lakukan pemesanan ulang.
                        </p>
                        <a href="{{ route('shop') }}" class="btn btn-custom px-4">Kembali ke Toko</a>
                    </div>
                </div>
            </div>
        @else
            {{-- TAMPILAN COUNTDOWN (Jika waktu masih ada) --}}
            <div class="row justify-content-center mb-4">
                <div class="col-12 text-center">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                    <h2 class="mt-3" style="font-family: 'Playfair Display', serif;">Selesaikan Pembayaran</h2>
                    
                    <div class="mt-3 mb-2">
                        <p class="text-muted mb-1">Batas waktu pembayaran:</p>
                        {{-- Badge Timer --}}
                        <div id="countdown-display" class="badge bg-danger fs-5 px-4 py-2 rounded-pill shadow-sm">
                            {{-- Tampilkan format awal dari PHP agar tidak kosong saat JS loading --}}
                            {{ sprintf('%02d:%02d', floor($remainingSeconds / 60), $remainingSeconds % 60) }}
                        </div>
                    </div>
                    
                    <p class="text-muted small">Pesanan #{{ $order->id }} akan otomatis dibatalkan jika waktu habis.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4">
                        {{-- Info Rekening --}}
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h5 class="mb-4" style="color: var(--primary-color);">1. Transfer Manual</h5>
                                    
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-3">
                                        <div>
                                            <div class="fw-bold">BCA</div>
                                            <div class="small text-muted">Gema Sandang Official</div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-dark">123-456-7890</h5>
                                            <small class="text-primary" style="cursor: pointer;" onclick="navigator.clipboard.writeText('1234567890'); alert('Tersalin!');">Salin</small>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning mb-0">
                                        <small class="d-block text-muted mb-1">Total Bayar:</small>
                                        <h4 class="mb-0 fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Upload --}}
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h5 class="mb-4" style="color: var(--primary-color);">2. Kirim Bukti</h5>
                                    <form action="{{ route('checkout.payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-4 text-center p-4 border border-dashed rounded bg-light">
                                            <i class="bi bi-cloud-upload display-4 text-muted mb-2"></i>
                                            <input type="file" name="bukti_bayar" class="form-control" required>
                                        </div>
                                        <button type="submit" class="btn btn-custom w-100 py-2">
                                            <i class="bi bi-send-fill me-2"></i> Kirim Bukti
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    {{-- KONDISI 2: Menunggu Konfirmasi (Bukti sudah ada) --}}
    @elseif($order->status == 'menunggu_konfirmasi' || $order->bukti_bayar)
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="card border-0 shadow-sm p-5">
                    <div class="mb-3"><i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i></div>
                    <h2 class="mb-3" style="font-family: 'Playfair Display', serif;">Bukti Diterima!</h2>
                    <p class="text-muted mb-4">Terima kasih. Kami sedang memverifikasi pembayaran Anda.</p>
                    <a href="{{ route('orders.index') }}" class="btn btn-custom px-4">Lihat Pesanan Saya</a>
                </div>
            </div>
        </div>

    {{-- KONDISI 3: Pesanan Dibatalkan --}}
    @elseif($order->status == 'dibatalkan')
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="card border-0 shadow-sm p-5">
                    <div class="mb-3"><i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i></div>
                    <h2 class="mb-3" style="font-family: 'Playfair Display', serif;">Pesanan Dibatalkan</h2>
                    <p class="text-muted mb-4">Waktu pembayaran habis atau pesanan dibatalkan.</p>
                    <a href="{{ route('shop') }}" class="btn btn-custom px-4">Belanja Lagi</a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen timer
        const timerElement = document.getElementById('countdown-display');
        
        // Hanya jalankan jika elemen ada dan statusnya menunggu pembayaran
        if (timerElement) {
            // Ambil sisa detik dari PHP (pastikan ini angka integer)
            let timeLeft = parseInt("{{ $remainingSeconds }}");

            // Fungsi untuk update tampilan
            function updateDisplay(seconds) {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                // Format "00:00"
                timerElement.textContent = 
                    (m < 10 ? "0" : "") + m + ":" + 
                    (s < 10 ? "0" : "") + s;
            }

            // Jalankan interval setiap 1 detik
            const countdownInterval = setInterval(() => {
                // Kurangi 1 detik
                timeLeft--;

                // Update tampilan
                updateDisplay(timeLeft);

                // Cek jika waktu habis
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    timerElement.innerHTML = "WAKTU HABIS";
                    timerElement.classList.remove('bg-danger');
                    timerElement.classList.add('bg-secondary');
                    
                    // Reload halaman agar status terupdate (jika backend membatalkan)
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }, 1000);
        }
    });
</script>
@endsection