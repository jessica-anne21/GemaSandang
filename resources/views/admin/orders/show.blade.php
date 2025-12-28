@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800" style="font-family: 'Playfair Display', serif; color: #800000;">
            </h1>
            <p class="text-muted small mb-0">Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4"> 

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: #800000; letter-spacing: 1px;">
                        <i class="bi bi-truck me-2"></i> Logistik & Resi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-2 text-muted small">Status Transaksi Saat Ini:</div>
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

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Update Status Pesanan</label>
                            <select name="status" class="form-select form-select-sm shadow-none">
                                <option value="menunggu_pembayaran" {{ $order->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="menunggu_konfirmasi" {{ $order->status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses (Siap Kirim)</option>
                                <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Nomor Resi Pengiriman</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash"></i></span>
                                <input type="text" name="nomor_resi" class="form-control shadow-none border-start-0" 
                                       value="{{ $order->nomor_resi }}" placeholder="Masukkan resi kurir...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm w-100 py-2" style="background-color: #800000; border: none;">Update Data Pesanan</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: #800000; letter-spacing: 1px;"><i class="bi bi-person me-2"></i> Detail Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="small mb-3">
                        <div class="text-muted fw-bold">Nama</div>
                        <div class="fw-bold">{{ $order->user->name ?? 'Guest' }}</div>
                    </div>
                    <div class="small mb-3">
                        <div class="text-muted fw-bold">Email</div>
                        <div class="fw-bold">{{ $order->user->email ?? '-' }}</div>
                    </div>
                    <div class="small mb-3">
                        <div class="text-muted fw-bold">Nomor HP:</div>
                        <div class="fw-bold">{{ $order->nomor_hp ?? $order->user->nomor_hp ?? '-' }}</div>
                    </div>
                    <div class="small mb-3">
                        <div class="text-muted fw-bold">Alamat Pengiriman:</div>
                        <div class="p-2 bg-light rounded text-secondary shadow-sm" style="font-size: 0.85rem;">{{ $order->alamat_pengiriman }}</div>
                    </div>

                    <div class="small mt-4">
                        <div class="text-danger fw-bold mb-1 small text-uppercase" style="letter-spacing: 0.5px;"><i class="bi bi-pencil-square me-1"></i> Catatan Pembeli:</div>
                        <div class="p-2 rounded border-start border-danger border-3 bg-light fst-italic shadow-sm">
                            @if($order->catatan_customer)
                                "{{ $order->catatan_customer }}"
                            @else
                                <span class="text-muted small fst-normal">Tidak ada catatan dari pembeli.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if($order->bukti_bayar)
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: #800000; letter-spacing: 1px;"><i class="bi bi-cash-stack me-2"></i> Verifikasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <a href="{{ asset('storage/' . $order->bukti_bayar) }}" target="_blank">
                                <img src="{{ asset('storage/' . $order->bukti_bayar) }}" class="img-fluid rounded shadow-sm border" style="max-height: 250px; object-fit: contain;">
                            </a>
                        </div>
                        <div class="col-md-7">
                            @if($order->status == 'menunggu_konfirmasi')
                                <div class="alert alert-info border-0 small py-2 mb-3">
                                    <i class="bi bi-info-circle-fill me-1"></i> Periksa mutasi bank sebelum melakukan konfirmasi di bawah ini.
                                </div>
                                <div class="d-grid gap-2">
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="btn btn-success w-100 shadow-sm border-0 py-2 mb-1">
                                            <i class="bi bi-check-lg me-1"></i> Terima Pembayaran
                                        </button>
                                    </form>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ asset('storage/' . $order->bukti_bayar) }}" download class="btn btn-sm btn-outline-dark w-100"><i class="bi bi-download"></i> Simpan</a>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle"></i> Tolak</button>
                                        </div>
                                    </div>
                                </div>
                            @elseif(in_array($order->status, ['diproses', 'dikirim', 'selesai']))
                                <div class="alert alert-success border-0 py-3 mb-0 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                        <div>
                                            <strong class="d-block">Pembayaran Terverifikasi</strong>
                                            <span class="small">Uang berhasil masuk.</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $order->bukti_bayar) }}" download class="btn btn-sm btn-link text-muted mt-2 text-decoration-none small p-0">
                                    <i class="bi bi-download me-1"></i> Download Bukti
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header border-0"><h5 class="modal-title fw-bold">Tolak Pembayaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <label class="form-label fw-bold small text-muted">Alasan Penolakan</label>
                                <textarea class="form-control shadow-none border-0 bg-light" name="catatan_admin" rows="3" required placeholder="Contoh: Bukti transfer terpotong atau nominal tidak sesuai..."></textarea>
                                <p class="text-danger x-small mt-2"><i class="bi bi-info-circle me-1"></i> Menolak akan meminta pelanggan mengupload ulang bukti pembayaran.</p>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm px-3">Ya, Tolak Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-light border shadow-sm text-center mb-4 py-4" style="border-radius: 0.75rem;">
                <i class="bi bi-hourglass-split display-6 text-muted mb-2 d-block"></i>
                <span class="text-muted">Pelanggan belum mengupload bukti pembayaran.</span>
            </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
                <div class="card-header bg-white border-0 py-3"><h6 class="mb-0 fw-bold text-uppercase" style="color: #800000; letter-spacing: 1px;"><i class="bi bi-bag-check me-2"></i> Produk yang Dipesan</h6></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr><th class="p-3 border-0">Item Produk</th><th class="p-3 border-0 text-center">Qty</th><th class="p-3 border-0 text-end">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="p-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item->product->foto_produk) }}" class="rounded border me-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div><div class="fw-bold mb-0 small">{{ $item->product->nama_produk }}</div><small class="text-muted">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</small></div>
                                        </div>
                                    </td>
                                    <td class="text-center small">x{{ $item->kuantitas }}</td>
                                    <td class="text-end fw-bold small text-dark">Rp {{ number_format($item->harga_saat_beli * $item->kuantitas, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="2" class="text-end p-3 px-3">TOTAL PEMBAYARAN</td>
                                <td class="text-end p-3 px-3" style="color: #800000; font-size: 1.1rem;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection