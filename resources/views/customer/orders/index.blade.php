@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h1 class="display-5 mb-4" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan Saya</h1>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="table-responsive"> 
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">ID Pesanan</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end">Total Belanja</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order) 
                            <tr>
                                <td class="fw-bold">#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d F Y') }}</td>
                                <td>
                                    @if($order->status == 'menunggu_pembayaran')
                                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                        <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-sm btn-primary ms-2 shadow-sm">Bayar</a>
                                    
                                    @elseif($order->status == 'menunggu_konfirmasi')
                                        <span class="badge bg-info text-dark">Menunggu Konfirmasi</span>

                                    @elseif($order->status == 'diproses')
                                        <span class="badge bg-secondary text-white">Sedang Diproses</span>
                                        <div class="x-small text-muted mt-1" style="font-size: 0.75rem;">Baju thrift-mu sedang dikemas</div>
                                    
                                    @elseif($order->status == 'dikirim')
                                        <span class="badge bg-primary mb-2">Sedang Dikirim</span>
                                        <div class="small fw-bold text-dark mb-2"><i class="bi bi-truck"></i> Resi: {{ $order->nomor_resi }}</div>
                                        
                                        <form action="{{ route('orders.complete', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success shadow-sm" onclick="return confirm('Apakah barang sudah benar-benar diterima dan dalam kondisi baik?')">
                                                <i class="bi bi-check-circle"></i> Selesaikan Pesanan
                                            </button>
                                        </form>
                                    
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                        <div class="small text-muted mt-1">
                                            Diterima: {{ $order->tanggal_diterima ? \Carbon\Carbon::parse($order->tanggal_diterima)->format('d M Y') : $order->updated_at->format('d M Y') }}
                                        </div>
                                    
                                    @elseif($order->status == 'dibatalkan')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $order->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="detailModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold">Detail Pesanan #{{ $order->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            @if($order->status == 'dibatalkan' && $order->catatan_admin)
                                                <div class="alert alert-danger border-0">
                                                    <strong>Info dari Admin:</strong> <br>
                                                    {{ $order->catatan_admin }}
                                                </div>
                                            @endif

                                            <h6 class="fw-bold mb-3"><i class="bi bi-bag-check me-2"></i>Produk yang dibeli:</h6>
                                            <ul class="list-group list-group-flush mb-3">
                                                @foreach ($order->items as $item)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <div>
                                                            <div class="fw-bold">{{ $item->product->nama_produk ?? 'Produk tidak tersedia' }}</div>
                                                            <small class="text-muted">
                                                                {{ $item->kuantitas }} x Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}
                                                            </small>
                                                        </div>
                                                        <span class="fw-bold">Rp {{ number_format($item->kuantitas * $item->harga_saat_beli, 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                                                <span>Total Bayar:</span>
                                                <span class="text-danger">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            @if($order->status == 'menunggu_pembayaran')
                                                <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-primary">Bayar Sekarang</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-cart-x display-4 mb-3 d-block"></i>
                                    <p class="mb-0">Anda belum memiliki riwayat pesanan.</p>
                                    <a href="{{ route('shop') }}" class="btn btn-sm btn-dark mt-2">Mulai Belanja</a>
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