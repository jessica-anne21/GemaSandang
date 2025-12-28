@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Kelola Pesanan</h1>
        <span class="badge bg-light text-dark border p-2">Total: {{ $orders->total() }} Pesanan</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3 border-0">ID</th>
                        <th class="p-3 border-0">Pelanggan</th>
                        <th class="p-3 border-0">Total Harga</th>
                        <th class="p-3 border-0">Status</th>
                        <th class="p-3 border-0">Tanggal</th>
                        <th class="p-3 border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="p-3 fw-bold">#{{ $order->id }}</td>
                            <td class="p-3">
                                <div class="fw-bold">{{ $order->user->name ?? 'Guest' }}</div>
                                <small class="text-muted">{{ $order->nomor_hp ?? '-' }}</small>
                            </td>
                            <td class="p-3">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="p-3">
                                @if($order->status == 'menunggu_pembayaran')
                                    <span class="badge bg-warning text-dark">Menunggu Bayar</span>
                                @elseif($order->status == 'menunggu_konfirmasi')
                                    <span class="badge bg-info text-dark">Cek Bukti</span>
                                @elseif($order->status == 'dikirim')
                                    <span class="badge bg-primary">Dikirim</span>
                                @elseif($order->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="p-3 small text-muted">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- BAGIAN PAGINATION --}}
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Agar pagination Bootstrap terlihat lebih manis */
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }
    .page-link {
        color: var(--primary-color);
    }
</style>
@endsection