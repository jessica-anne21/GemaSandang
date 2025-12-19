@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800" style="font-family: 'Playfair Display', serif;">Daftar Negosiasi Harga</h1>

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
                                    <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill">Terima</button>
                                    </form>
                                    <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Tolak</button>
                                    </form>
                                @else
                                    <small class="text-muted">{{ $bargain->updated_at->format('d/m/y') }}</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada tawaran masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection