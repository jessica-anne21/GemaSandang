@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h1 class="display-5 mb-4" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan Saya</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive"> 
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-end">Total Belanja</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order) 
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d F Y') }}</td>
                                <td>
                                    @if($order->status == 'menunggu_pembayaran')
                                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                        <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-sm btn-primary ms-2">Bayar</a>
                                    
                                    @elseif($order->status == 'menunggu_konfirmasi')
                                        <span class="badge bg-info text-dark">Menunggu Konfirmasi</span>
                                    
                                    @elseif($order->status == 'dikirim')
                                        <span class="badge bg-primary mb-2">Sedang Dikirim</span>
                                        <div class="small text-muted mb-2">Resi: {{ $order->nomor_resi }}</div>
                                        
                                        <form action="{{ route('orders.complete', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah barang sudah benar-benar diterima dan dalam kondisi baik?')">
                                                <i class="bi bi-check-circle"></i> Pesanan Diterima
                                            </button>
                                        </form>
                                    
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                        <div class="small text-muted mt-1">
                                            Diterima: {{ \Carbon\Carbon::parse($order->tanggal_diterima)->format('d M Y') }}
                                        </div>
                                    
                                    @elseif($order->status == 'dibatalkan')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $order->id }}">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="detailModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Pesanan #{{ $order->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            
                                            @if($order->status == 'dibatalkan' && $order->catatan_admin)
                                                <div class="alert alert-danger">
                                                    <strong>Alasan Pembatalan:</strong> <br>
                                                    {{ $order->catatan_admin }}
                                                </div>
                                            @endif

                                            <h6 class="fw-bold mb-3">Produk yang dibeli:</h6>
                                            <ul class="list-group mb-3">
                                                @foreach ($order->items as $item)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="fw-bold">{{ $item->product->nama_produk ?? 'Produk tidak tersedia' }}</div>
                                                            
                                                            <small class="text-muted">
                                                                {{ $item->kuantitas }} x Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}
                                                            </small>
                                                        </div>

                                                        <span>Rp {{ number_format($item->kuantitas * $item->harga_saat_beli, 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="d-flex justify-content-between fw-bold fs-5 mt-4">
                                                <span>Total Bayar:</span>
                                                <span class="text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            @if($order->status == 'menunggu_pembayaran')
                                                <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-primary">Bayar Sekarang</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="mb-0">Anda belum memiliki riwayat pesanan.</p>
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