@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">
                Detail Pesanan #{{ $order->id }}
            </h1>
            <p class="text-muted small mb-0">Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4"> 

        <div class="col-lg-4">
            
            {{-- Card 1: Update Status --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--primary-color); letter-spacing: 1px;">
                        <i class="bi bi-gear me-2"></i> Status Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    
                    <div class="text-center mb-4">
                        <div class="mb-2 text-muted small">Status Saat Ini</div>
                        @if($order->status == 'menunggu_pembayaran')
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Pembayaran</span>
                        @elseif($order->status == 'menunggu_konfirmasi')
                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Menunggu Konfirmasi</span>
                        @elseif($order->status == 'dikirim')
                            <span class="badge bg-primary px-3 py-2 rounded-pill">Sedang Dikirim</span>
                        @elseif($order->status == 'selesai')
                            <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                        @elseif($order->status == 'dibatalkan')
                            <span class="badge bg-danger px-3 py-2 rounded-pill">Dibatalkan</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>

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
                                   value="{{ $order->nomor_resi }}" placeholder="Input resi jika dikirim...">
                        </div>

                        <button type="submit" class="btn btn-custom btn-sm w-100">Update Status</button>
                    </form>
                </div>
            </div>

            {{-- Card 2: Informasi Pelanggan --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--primary-color); letter-spacing: 1px;">
                        <i class="bi bi-person me-2"></i> Data Pelanggan
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom">
                            <span class="text-muted small">Nama</span>
                            <span class="fw-bold text-end">{{ $order->user->name ?? 'Guest' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom">
                            <span class="text-muted small">Email</span>
                            <span class="text-end text-break small">{{ $order->user->email ?? '-' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom">
                            <span class="text-muted small">Nomor HP</span>
                            <span class="fw-bold text-end">
                                {{ $order->nomor_hp ?? $order->user->nomor_hp ?? '-' }}
                            </span>
                        </li>
                    </ul>
                    
                    <div class="mt-3">
                        <label class="small text-muted fw-bold mb-1">Alamat Pengiriman</label>
                        <div class="p-3 bg-light rounded border small text-secondary">
                            <i class="bi bi-geo-alt me-1"></i> {{ $order->alamat_pengiriman }}
                        </div>
                    </div>

                    {{-- Menampilkan Ongkir dan Kurir --}}
                    <div class="mt-3">
                        <label class="small text-muted fw-bold mb-1">Pengiriman</label>
                        <div class="d-flex justify-content-between align-items-center p-2 border rounded bg-white">
                            <span class="text-uppercase fw-bold text-primary small">{{ $order->kurir ?? 'KURIR' }}</span>
                            <span class="small">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="small text-muted fw-bold mb-1">Catatan Pelanggan</label>
                        <div class="p-3 bg-light rounded border small text-secondary">
                            {{ $order->catatan_customer }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-8">
            @if($order->bukti_bayar)
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--primary-color); letter-spacing: 1px;">
                        <i class="bi bi-receipt me-2"></i> Bukti Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-1 d-inline-block bg-light">
                                <img src="{{ asset('storage/' . $order->bukti_bayar) }}" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <p class="text-muted small mb-2">
                                Pelanggan telah mengupload bukti pembayaran. Silakan cek validitasnya sebelum mengubah status menjadi <strong>"Dikirim"</strong>.
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $order->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-zoom-in me-1"></i> Lihat Ukuran Penuh
                                </a>
                                <a href="{{ asset('storage/' . $order->bukti_bayar) }}" download class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="bi bi-x-circle me-1"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="catatan_admin" class="form-label">Alasan Penolakan</label>
                                    <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="3" required placeholder="Contoh: Foto buram, nominal tidak sesuai, dll."></textarea>
                                </div>
                                <p class="text-muted small">Tindakan ini akan menghapus bukti bayar saat ini dan meminta pelanggan mengupload ulang.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Tolak & Minta Re-upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--primary-color); letter-spacing: 1px;">
                        <i class="bi bi-bag me-2"></i> Rincian Produk
                    </h6>
                    <span class="badge bg-light text-dark border">
                        Total: {{ $order->items->sum('kuantitas') }} Item
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="p-3 border-0" style="width: 50%">Produk</th>
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
                                            <div class="rounded border p-1 me-3 bg-white" style="width: 50px; height: 50px;">
                                                <img src="{{ asset('storage/' . $item->product->foto_produk) }}" 
                                                     alt="{{ $item->product->nama_produk }}" 
                                                     class="w-100 h-100 rounded" 
                                                     style="object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">{{ $item->product->nama_produk }}</div>
                                                <small class="text-muted">{{ $item->product->category->nama_kategori ?? 'Kategori' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center text-muted">x{{ $item->kuantitas }}</td>
                                    <td class="text-end small text-muted">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-dark">Rp {{ number_format($item->harga_saat_beli * $item->kuantitas, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-white border-top">
                            <tr>
                                <td colspan="3" class="text-end p-2 text-muted small">Subtotal Produk</td>
                                <td class="text-end p-2 small">Rp {{ number_format($order->total_harga - ($order->ongkir ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end p-2 text-muted small">Biaya Pengiriman ({{ strtoupper($order->kurir ?? 'Ekspedisi') }})</td>
                                <td class="text-end p-2 small">Rp {{ number_format($order->ongkir ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end p-3 border-0"><h5 class="mb-0 fw-bold" style="color: var(--primary-color);">Total Bayar</h5></td>
                                <td class="text-end p-3 border-0"><h5 class="mb-0 fw-bold" style="color: var(--primary-color);">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection