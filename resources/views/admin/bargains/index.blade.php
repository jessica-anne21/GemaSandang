@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800" style="font-family: 'Playfair Display', serif; color: #800000;">Daftar Negosiasi Harga</h1>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="p-3 ps-4">Produk</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Stok</th> 
                        <th class="p-3">Harga Asli</th>
                        <th class="p-3">Tawaran</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bargains as $bargain)
                        <tr>
                            <td class="p-3 ps-4">
                                <div class="fw-bold">{{ $bargain->product->nama_produk }}</div>
                                <small class="text-muted">ID: #{{ $bargain->product->id }}</small>
                            </td>
                            <td class="p-3">
                                <div class="small fw-bold">{{ $bargain->user->name }}</div>
                                <div class="small text-muted">{{ $bargain->user->email }}</div>
                            </td>

                            {{-- DATA STOK --}}
                            <td class="p-3">
                                @if($bargain->product->stok > 0)
                                    <span class="badge bg-info text-dark rounded-pill">{{ $bargain->product->stok }} pcs</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Habis</span>
                                @endif
                            </td>

                            <td class="p-3">Rp {{ number_format($bargain->product->harga, 0, ',', '.') }}</td>
                            <td class="p-3 fw-bold text-danger">Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}</td>
                            
                            <td class="p-3 text-center">
                                @if($bargain->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                @elseif($bargain->status == 'accepted')
                                    <span class="badge bg-success rounded-pill px-3">Diterima</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">Ditolak</span>
                                @endif
                            </td>
                            
                            <td class="p-3 text-end pe-4">
                                @if($bargain->status == 'pending')
                                    
                                    {{-- TOMBOL TERIMA (Hanya jika stok ada) --}}
                                    @if($bargain->product->stok > 0)
                                        <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Yakin ingin menerima tawaran ini?')">
                                                <i class="bi bi-check-lg"></i> Terima
                                            </button>
                                        </form>
                                    @else
                                        {{-- Disable tombol terima kalau stok habis --}}
                                        <button class="btn btn-sm btn-secondary rounded-pill px-3" disabled title="Stok Habis">
                                            <i class="bi bi-x-circle"></i> Habis
                                        </button>
                                    @endif

                                    {{-- TOMBOL TOLAK --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $bargain->id }}">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>

                                @else
                                    {{-- KONDISI JIKA STATUS SUDAH SELESAI --}}

                                    @if($bargain->status == 'accepted')
                                        <small class="text-success fw-bold d-block mb-1">
                                            <i class="bi bi-check-circle-fill"></i> Deal
                                        </small>
                                        
                                        {{-- TOMBOL BATALKAN DEAL --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#cancelDealModal-{{ $bargain->id }}">
                                            <i class="bi bi-arrow-counterclockwise"></i> Batalkan
                                        </button>

                                        {{-- MODAL BATALKAN DEAL --}}
                                        <div class="modal fade" id="cancelDealModal-{{ $bargain->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title fs-6 fw-bold">Batalkan Kesepakatan?</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        {{-- Ubah status jadi Ditolak --}}
                                                        <input type="hidden" name="status" value="rejected"> 
                                                        
                                                        <div class="modal-body text-start">
                                                            <p class="small text-muted">Tindakan ini akan membatalkan status "Diterima" milik <strong>{{ $bargain->user->name }}</strong>.</p>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small">Alasan Pembatalan:</label>
                                                                <textarea class="form-control" name="catatan_admin" rows="2" required>Maaf, kesepakatan dibatalkan karena melebihi batas waktu pembayaran.</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer py-1">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-sm btn-danger">Ya, Batalkan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        <small class="text-muted d-block">{{ $bargain->updated_at->format('d/m/y H:i') }}</small>
                                        
                                        @if($bargain->catatan_admin)
                                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted mt-1" data-bs-toggle="modal" data-bs-target="#reasonViewModal-{{ $bargain->id }}">
                                                <i class="bi bi-info-circle"></i> Lihat Alasan
                                            </button>

                                            {{-- Modal Lihat Alasan --}}
                                            <div class="modal fade" id="reasonViewModal-{{ $bargain->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-light py-2">
                                                            <h6 class="modal-title fw-bold" style="font-size: 0.9rem;">Alasan Penolakan</h6>
                                                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <p class="mb-0 text-danger small">{{ $bargain->catatan_admin }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="rejectModal-{{ $bargain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title fs-5">Tolak Tawaran</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">

                                        <div class="modal-body text-start">
                                            <p>Anda akan menolak tawaran sebesar <strong>Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}</strong> untuk produk <strong>{{ $bargain->product->nama_produk }}</strong>.</p>
                                            
                                            <div class="mb-3">
                                                <label for="catatan_admin" class="form-label fw-bold">Alasan Penolakan:</label>
                                                <textarea class="form-control" name="catatan_admin" rows="3" placeholder="Contoh: Tawaran terlalu rendah. Minimal Rp 150.000 ya." required></textarea>
                                                <div class="form-text">Pesan ini akan muncul di halaman riwayat customer.</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill">Konfirmasi Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada negosiasi harga masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection