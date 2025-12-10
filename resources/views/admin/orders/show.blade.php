@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- =============================== --}}
    {{-- HEADER --}}
    {{-- =============================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="font-family:'Playfair Display', serif; color:var(--primary-color);">
                Detail Pesanan #{{ $order->id }}
            </h1>
            <p class="text-muted small mb-0">
                Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB
            </p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- =============================== --}}
    {{-- ALERT SUCCESS --}}
    {{-- =============================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">

        {{-- ===================================================== --}}
        {{-- KOLOM KIRI : STATUS PESANAN + DATA CUSTOMER + BUKTI --}}
        {{-- ===================================================== --}}
        <div class="col-lg-4">

            {{-- CARD: STATUS --}}
             <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold text-uppercase mb-0" style="color:var(--primary-color); letter-spacing:1px;">
                        <i class="bi bi-gear me-2"></i> Status Pesanan
                    </h6>
                </div>

                <div class="card-body">

                    {{-- Status Saat Ini --}}
                    <div class="text-center mb-4">
                        <div class="mb-2 text-muted small">Status Saat Ini</div>

                        @php
                            $statusClass = [
                                'menunggu_pembayaran' => 'warning text-dark',
                                'menunggu_konfirmasi' => 'info text-dark',
                                'dikirim' => 'primary',
                                'selesai' => 'success',
                                'dibatalkan' => 'danger',
                            ];
                        @endphp

                        <span class="badge bg-{{ $statusClass[$order->status] ?? 'secondary' }} px-3 py-2 rounded-pill">
                            {{ ucwords(str_replace('_',' ', $order->status)) }}
                        </span>
                    </div>

                    {{-- Form Update --}}
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Ubah Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="menunggu_pembayaran" {{ $order->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="menunggu_konfirmasi" {{ $order->status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Nomor Resi</label>
                            <input type="text" name="nomor_resi" class="form-control form-control-sm"
                                   value="{{ $order->nomor_resi }}"
                                   placeholder="Isi nomor resi jika pesanan dikirim...">
                        </div>

                        <button type="submit" class="btn btn-custom btn-sm w-100">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- CARD: INFORMASI PELANGGAN --}}
            <div class="card shadow-sm border-0 mb-4 rounded-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold text-uppercase mb-0" style="color:var(--primary-color); letter-spacing:1px;">
                        <i class="bi bi-person me-2"></i> Data Pelanggan
                    </h6>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between small">
                            <span class="text-muted">Nama</span>
                            <span class="fw-bold text-end">{{ $order->user->name ?? 'Guest' }}</span>
                        </li>

                        <li class="list-group-item px-0 d-flex justify-content-between small">
                            <span class="text-muted">Email</span>
                            <span class="text-end text-break">{{ $order->user->email ?? '-' }}</span>
                        </li>

                        <li class="list-group-item px-0 d-flex justify-content-between small">
                            <span class="text-muted">Nomor HP</span>
                            <span class="fw-bold text-end">{{ $order->nomor_hp ?? $order->user->nomor_hp ?? '-' }}</span>
                        </li>
                    </ul>

                    {{-- Alamat --}}
                    <div class="mt-3">
                        <label class="small text-muted fw-bold">Alamat Pengiriman</label>
                        <div class="p-3 bg-light border rounded small text-secondary">
                            <i class="bi bi-geo-alt me-1"></i> {{ $order->alamat_pengiriman }}
                        </div>
                    </div>

                    {{-- Pengiriman --}}
                    <div class="mt-3">
                        <label class="small text-muted fw-bold">Pengiriman</label>

                        <div class="d-flex justify-content-between p-2 border rounded bg-white small">
                            <span class="fw-bold text-primary">{{ strtoupper($order->kurir ?? 'KURIR') }}</span>
                            <span>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD: BUKTI BAYAR --}}
            @if($order->bukti_bayar)
            <div class="card shadow-sm border-0 mb-4 rounded-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold text-uppercase mb-0" style="color:var(--primary-color); letter-spacing:1px;">
                        <i class="bi bi-receipt me-2"></i> Bukti Transfer
                    </h6>
                </div>

                <div class="card-body">
                    <div class="row g-3 align-items-center">

                        <div class="col-6 text-center">
                            <div class="rounded border overflow-hidden" style="height:150px;">
                                <img src="{{ asset('storage/' . $order->bukti_bayar) }}"
                                     class="img-fluid h-100 w-100"
                                     style="object-fit:contain;">
                            </div>
                        </div>

                        <div class="col-6">
                            <a href="{{ asset('storage/' . $order->bukti_bayar) }}"
                               target="_blank"
                               class="btn btn-outline-secondary btn-sm w-100 mb-2">
                                <i class="bi bi-zoom-in me-1"></i> Lihat Full Size
                            </a>

                            <a href="{{ asset('storage/' . $order->bukti_bayar) }}"
                               download
                               class="btn btn-custom btn-sm w-100">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- ======================================== --}}
        {{-- KOLOM KANAN : RINCIAN PRODUK --}}
        {{-- ======================================== --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-uppercase mb-0" style="color:var(--primary-color); letter-spacing:1px;">
                        <i class="bi bi-bag me-2"></i> Rincian Produk
                    </h6>

                    <span class="badge bg-light text-dark border">
                        Total: {{ $order->items->sum('kuantitas') }} Item
                    </span>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="p-3 border-0">Produk</th>
                                <th class="p-3 border-0 text-center">Qty</th>
                                <th class="p-3 border-0 text-end">Harga</th>
                                <th class="p-3 border-0 text-end">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="border rounded p-1 me-3 bg-white"
                                                 style="width:50px; height:50px;">
                                                <img src="{{ asset('storage/' . $item->product->foto_produk) }}"
                                                     class="w-100 h-100 rounded"
                                                     style="object-fit:cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-truncate" style="max-width:200px;">
                                                    {{ $item->product->nama_produk }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $item->product->category->nama_kategori ?? 'Kategori' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center text-muted">
                                        x{{ $item->kuantitas }}
                                    </td>

                                    <td class="text-end small text-muted">
                                        Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($item->harga_saat_beli * $item->kuantitas, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        {{-- FOOTER TOTAL --}}
                        <tfoot class="bg-white">
                            <tr>
                                <td colspan="3" class="text-end p-2 small text-muted">Subtotal Produk</td>
                                <td class="text-end p-2 small">
                                    Rp {{ number_format($order->total_harga - $order->ongkir, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end p-2 small text-muted">
                                    Biaya Pengiriman ({{ strtoupper($order->kurir ?? 'Ekspedisi') }})
                                </td>
                                <td class="text-end p-2 small">
                                    Rp {{ number_format($order->ongkir, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end p-3 border-0">
                                    <h5 class="fw-bold m-0" style="color:var(--primary-color);">Total Bayar</h5>
                                </td>
                                <td class="text-end p-3 border-0">
                                    <h5 class="fw-bold m-0" style="color:var(--primary-color);">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </h5>
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
