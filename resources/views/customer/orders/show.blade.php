@extends('layouts.main')

@section('content')
<div class="container my-5">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-4 shadow-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 15px;">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Detail Pesanan #{{ $order->id }}</h5>
                    <small class="text-muted">{{ $order->created_at->format('d F Y, H:i') }} WIB</small>
                </div>
                @if($order->status == 'menunggu_pembayaran')
                    <span class="badge bg-warning text-dark px-3 py-2">Menunggu Pembayaran</span>
                @elseif($order->status == 'diproses')
                    <span class="badge bg-secondary text-white px-3 py-2">Sedang Diproses</span>
                @elseif($order->status == 'dikirim')
                    <span class="badge bg-primary px-3 py-2">Sedang Dikirim</span>
                @elseif($order->status == 'selesai')
                    <span class="badge bg-success px-3 py-2">Selesai</span>
                @elseif($order->status == 'dibatalkan')
                    <span class="badge bg-danger px-3 py-2">Dibatalkan</span>
                @endif
            </div>
        </div>

        <div class="card-body p-4">
            @if($order->nomor_resi)
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-truck fs-4 me-3"></i>
                <div>
                    <strong>Nomor Resi:</strong> <span class="user-select-all">{{ $order->nomor_resi }}</span>
                    <div class="small">Silakan cek resi ini di website ekspedisi terkait.</div>
                </div>
            </div>
            @endif

            <h6 class="fw-bold mb-3">Produk yang Dibeli:</h6>
            <div class="table-responsive mb-4">
                <table class="table table-borderless align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $item->product->nama_produk ?? 'Produk Dihapus' }}</div>
                                        <small class="text-muted">{{ $item->product->kategori->nama_kategori ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->kuantitas }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->harga_saat_beli * $item->kuantitas, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end pt-3"><strong>Total Belanja</strong></td>
                            <td class="text-end pt-3 fw-bold text-danger fs-5">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                @if($order->status == 'menunggu_pembayaran')
                    <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-primary px-4">Bayar Sekarang</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection