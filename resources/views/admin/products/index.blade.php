@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Kelola Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-custom rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
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
                        <th class="p-3 border-0 ps-4">Produk</th>
                        <th class="p-3 border-0">Kategori</th>
                        <th class="p-3 border-0">Harga</th>
                        <th class="p-3 border-0">Stok</th>
                        <th class="p-3 border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $product->foto_produk) }}" 
                                         class="rounded border me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="fw-bold">{{ $product->nama_produk }}</div>
                                </div>
                            </td>
                            <td class="p-3">{{ $product->category->nama_kategori }}</td>
                            <td class="p-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="p-3">
                                @if($product->stok <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $product->stok }} pcs</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-info rounded-circle">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-5 text-muted">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- NAVIGASI PAGINATION --}}
        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection