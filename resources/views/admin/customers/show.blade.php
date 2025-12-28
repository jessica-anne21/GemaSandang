@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                Profil Pelanggan
            </h1>
            <p class="text-muted small mb-0">Detail informasi dan riwayat transaksi</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            
            {{-- Card Profil --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center mx-auto mb-3 shadow" 
                         style="width: 100px; height: 100px; font-size: 3rem; font-weight: bold;">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-3 badge bg-light text-dark border">Customer</p>
                    
                    <hr class="my-4 opacity-10">
                    
                    <div class="text-start px-2">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Email</label>
                            <span class="text-dark"><i class="bi bi-envelope me-2"></i>{{ $customer->email }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Nomor HP</label>
                            <span class="text-dark"><i class="bi bi-telephone me-2"></i>{{ $customer->nomor_hp ?? '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Alamat Utama</label>
                            <span class="text-dark d-block bg-light p-2 rounded border small">
                                <i class="bi bi-geo-alt me-1"></i> {{ $customer->alamat ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Bergabung Sejak</label>
                            <span class="text-dark"><i class="bi bi-calendar3 me-2"></i>{{ $customer->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Statistik --}}
            <div class="card shadow-sm border-0 text-white" style="border-radius: 0.75rem; background: linear-gradient(135deg, var(--primary-color), #a05d69);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase opacity-75 mb-0" style="letter-spacing: 1px;">Total Belanja</h6>
                        <i class="bi bi-wallet2 fs-4 opacity-50"></i>
                    </div>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h2>
                    <div class="stat-box">
                        <p>Total Pesanan Selesai: <strong>{{ $totalPesananSelesai }}</strong></p>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--primary-color);">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Pesanan
                    </h6>
                    <span class="badge bg-light text-dark border rounded-pill">
                        Total: {{ $customer->orders->count() }} Transaksi
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="p-3 border-0 ps-4">ID Order</th>
                                <th class="p-3 border-0">Tanggal</th>
                                <th class="p-3 border-0">Total</th>
                                <th class="p-3 border-0 text-center">Status</th>
                                <th class="p-3 border-0 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->orders as $order)
                                <tr>
                                    <td class="p-3 ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                    <td class="p-3 small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="p-3 fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        @if($order->status == 'menunggu_pembayaran')
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Menunggu Bayar</span>
                                        @elseif($order->status == 'menunggu_konfirmasi')
                                            <span class="badge bg-info text-dark rounded-pill px-3 py-2">Perlu Cek</span>
                                        @elseif($order->status == 'dikirim')
                                            <span class="badge bg-primary rounded-pill px-3 py-2">Dikirim</span>
                                        @elseif($order->status == 'selesai')
                                            <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                                        @elseif($order->status == 'dibatalkan')
                                            <span class="badge bg-danger rounded-pill px-3 py-2">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-end pe-4">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x display-6 d-block mb-3 opacity-50"></i>
                                        Pelanggan ini belum pernah melakukan pemesanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection