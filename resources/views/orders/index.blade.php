@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h1 class="display-5 mb-4" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan Saya</h1>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive"> 
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-end">Total Belanja</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order) 
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d F Y') }}</td>
                                <td>
                                    <span class="badge bg-success text-capitalize">{{ $order->status }}</span>
                                </td>
                                <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-outline-dark">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="mb-0">Anda belum memiliki riwayat pesanan.</p>
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