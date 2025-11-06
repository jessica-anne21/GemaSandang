@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif;">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-muted">Ini adalah ringkasan aktivitas toko Anda hari ini.</p>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-primary">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Produk</h5>
                    <span class="fs-2 fw-bold">{{ $totalProducts }}</span>
                </div>
                <i class="bi bi-box-seam stat-card-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-secondary">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Pesanan Baru</h5>
                    <span class="fs-2 fw-bold">{{ $newOrders }}</span>
                </div>
                <i class="bi bi-card-checklist stat-card-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-success">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Pelanggan</h5>
                    <span class="fs-2 fw-bold">{{ $totalCustomers }}</span>
                </div>
                <i class="bi bi-people stat-card-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">

    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Grafik Penjualan</h5>
            </div>
            <div class="card-body">
                <p class="text-center text-muted p-5">
                    (Tempat untuk Chart.js atau grafik lainnya akan muncul di sini)
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Pesanan Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="p-3">
                                    <strong class="d-block">#{{ $order->id }} - {{ $order->user->name ?? 'Guest' }}</strong>
                                    <small class="text-muted">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</small>
                                </td>
                                <td class="text-end p-3">
                                    @if($order->status == 'pending')
                                        <span class="badge" style="background-color: var(--secondary-color); color: var(--bg-light);">Pending</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center p-4">
                                    <p class="mb-0 text-muted">Belum ada pesanan terbaru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection