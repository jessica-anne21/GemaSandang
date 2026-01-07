@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">
            Kelola Pelanggan
        </h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="p-3 border-0 ps-4">ID</th>
                            <th class="p-3 border-0">Nama Pelanggan</th>
                            <th class="p-3 border-0">Kontak</th>
                            <th class="p-3 border-0 text-center">Bergabung</th>
                            <th class="p-3 border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="p-3 ps-4 fw-bold">#{{ $customer->id }}</td>
                                <td class="p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm text-white" 
                                             style="width: 40px; height: 40px; font-size: 1.1rem; font-weight: bold; background-color: #800000;">
                                            {{ substr($customer->name, 0, 1) }}
                                        </div>
                                        <div class="fw-bold text-dark">{{ $customer->name }}</div>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div class="d-flex flex-column small">
                                        <span class="mb-1"><i class="bi bi-envelope me-2 text-muted"></i>{{ $customer->email }}</span>
                                        <span class="text-muted"><i class="bi bi-telephone me-2"></i>{{ $customer->nomor_hp ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="badge bg-light text-dark border fw-normal px-3 py-2 rounded-pill">
                                        {{ $customer->created_at->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="p-3 text-end pe-4">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-people display-4 mb-3 opacity-50"></i>
                                        <p class="mb-0">Tidak ada data pelanggan yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($customers->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: #800000 !important;
        border-color: #800000 !important;
    }
    .page-link {
        color: #800000;
    }
    .table thead th {
        letter-spacing: 0.05em;
    }
</style>
@endsection