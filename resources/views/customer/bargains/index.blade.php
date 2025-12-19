@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h3 class="mb-4" style="font-family: 'Playfair Display', serif;">
        Riwayat Tawaran Saya
    </h3>

    @if($bargains->isEmpty())
        <div class="alert alert-info shadow-sm">
            Anda belum pernah melakukan tawaran harga.
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="p-3">Produk</th>
                            <th class="p-3">Harga Asli</th>
                            <th class="p-3">Tawaran</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3">Catatan Admin</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bargains as $bargain)
                            <tr>
                                <td class="p-3 fw-bold">
                                    {{ $bargain->product->nama_produk }}
                                </td>
                                <td class="p-3">
                                    Rp {{ number_format($bargain->product->harga, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-danger fw-bold">
                                    Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center">
                                    @if($bargain->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($bargain->status === 'accepted')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    {{ $bargain->catatan_admin ?? '-' }}
                                </td>
                                <td class="p-3 text-center">
                                @if($bargain->status === 'accepted')
                                    <form action="{{ route('cart.add.bargain') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="bargain_id" value="{{ $bargain->id }}">
                                        <button class="btn btn-sm btn-success">
                                            Masukkan ke Keranjang
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
