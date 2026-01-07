@extends('layouts.main')

@section('content')
<div class="container my-5">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-4 shadow-sm rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">Detail Pesanan #{{ $order->id }}</h4>
                    <p class="text-muted small mb-0">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
                <div>
                    @php
                        $badgeClass = [
                            'menunggu_pembayaran' => 'bg-warning text-dark',
                            'menunggu_konfirmasi' => 'bg-info text-dark',
                            'diproses' => 'bg-secondary text-white',
                            'dikirim' => 'bg-primary',
                            'selesai' => 'bg-success',
                            'dibatalkan' => 'bg-danger'
                        ][$order->status] ?? 'bg-light';
                    @endphp
                    <span class="badge {{ $badgeClass }} px-4 py-2 rounded-pill shadow-sm">
                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            
        @if($order->status == 'dibatalkan')
        <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" style="border-radius: 10px; background-color: #f8d7da;">
            <i class="bi bi-exclamation-octagon-fill fs-3 me-3 text-danger"></i>
            <div>
                <strong class="d-block">Pesanan Dibatalkan Otomatis</strong>
                <span class="small">Silakan lakukan pemesanan ulang jika stok produk masih tersedia.</span>
            </div>
        </div>
        @endif
    
        @if($order->nomor_resi && $order->status == 'dikirim')
            <div class="alert alert-primary d-flex align-items-center mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-truck fs-3 me-3"></i>
                <div>
                    <strong class="d-block">Pesananmu dalam perjalanan!</strong>
                    <span class="small">Nomor Resi: </span><strong class="user-select-all">{{ $order->nomor_resi }}</strong>
                </div>
            </div>
        @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted" style="letter-spacing: 1px;">Produk yang Dibeli</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light small">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($item->product->foto_produk) }}" 
                                                 class="rounded border me-3 shadow-sm" 
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('images/default.jpg') }}'">
                                            <div>
                                                <div class="fw-bold small">{{ $item->product->nama_produk ?? 'Produk Dihapus' }}</div>
                                                <div class="fw-bold small">{{ $item->product->nama_produk ?? 'Produk Dihapus' }}</div>
                                                <small class="text-muted">{{ $item->product->category->nama_kategori ?? '-' }}</small>
                                                @if($item->harga_saat_beli < ($item->product->harga ?? 0))
                                                    <div class="mt-1">
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2" style="font-size: 0.7rem;">
                                                            <i class="bi bi-check2-circle me-1"></i> Harga Negosiasi
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center small">x{{ $item->kuantitas }}</td>
                                    <td class="text-end small">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold small">Rp {{ number_format($item->harga_saat_beli * $item->kuantitas, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light p-4 rounded-4 shadow-sm h-100">
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Informasi Pengiriman</h6>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Alamat Penerima:</div>
                            <div class="fw-bold small mb-2">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="small text-secondary">{{ $order->alamat_pengiriman }}</div>
                            <div class="mt-2 small"><span class="badge bg-white text-dark border">{{ strtoupper($order->kurir) }}</span></div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Ringkasan Biaya</h6>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($order->total_harga - $order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 fw-bold border-top pt-2">
                            <span>Total Bayar</span>
                            <span class="text-danger fs-5">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>

                        @if($order->status == 'menunggu_pembayaran')
                            <div class="d-grid mt-4">
                                <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-primary rounded-pill py-2 shadow-sm">
                                    <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection