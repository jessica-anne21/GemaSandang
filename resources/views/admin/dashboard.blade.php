@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif;">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-muted">Ini adalah ringkasan aktivitas toko Anda hari ini.</p>
</div>

{{-- Stat Cards --}}
<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-primary">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Produk</h5>
                    <span class="fs-2 fw-bold">{{ $totalProducts }}</span>
                </div>
                <i class="bi bi-box-seam stat-card-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-secondary">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Pendapatan</h5>
                    <span class="fs-2 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <i class="bi bi-card-checklist stat-card-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-success">
            <div class="card-body">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Pelanggan</h5>
                    <span class="fs-2 fw-bold">{{ $totalCustomers }}</span>
                </div>
                <i class="bi bi-people stat-card-icon"></i>
            </div>
        </div>
    </div>
</div>


<div class="row mt-3">

    {{-- Kolom Kiri: Grafik --}}
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Grafik Penjualan</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Pesanan Terbaru (MODIFIKASI LINK) --}}
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Pesanan Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @forelse ($recentOrders as $order)
                            {{-- BARIS YANG BISA DIKLIK --}}
                            {{-- Kita bungkus <tr> dalam <a> agar seluruh baris bisa diklik --}}
                            <tr class="clickable-row" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'" style="cursor: pointer;">
                                <td class="p-3">
                                    <strong class="d-block">#{{ $order->id }} - {{ $order->user->name ?? 'Guest' }}</strong>
                                    <small class="text-muted">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</small>
                                </td>
                                <td class="text-end p-3">
                                    @if($order->status == 'menunggu_pembayaran')
                                        <span class="badge bg-warning text-dark">Menunggu Bayar</span>
                                    @elseif($order->status == 'menunggu_konfirmasi')
                                        <span class="badge bg-info text-dark">Cek Bukti</span>
                                    @elseif($order->status == 'dikirim')
                                        <span class="badge bg-primary">Dikirim</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center p-4">
                                    <p class="mb-0 text-muted">Belum ada pesanan terbaru.</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartLabels = @json($labels ?? []);
    
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            data: @json($data ?? []),
            datasets: [{
                label: 'Penjualan per Hari (Rp)',
                data: @json($data ?? []),
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                borderColor: '#8D4B55', 
                backgroundColor: 'rgba(141, 75, 85, 0.2)', 
                pointBackgroundColor: '#8D4B55',
                pointRadius: 4,
            }]
        },
options: {
    responsive: true,
    plugins: {
        legend: { display: true },
    },
    scales: {
        y: {
            ticks: {
                callback: function(value) {
                    return 'Rp ' + value.toLocaleString('id-ID');
                }
            }
        }
    },
    
    // ============================================================
    // FUNGSI UNTUK MENGUBAH KURSOR MENJADI JEMPOL/POINTER
    // ============================================================
    onHover: (e, elements) => {
        // Mendapatkan elemen canvas (penting untuk mengubah kursor)
        const chartContainer = document.getElementById('salesChart');
        if (elements.length > 0) {
            // Jika kursor berada di atas titik data, ubah kursor menjadi tangan (pointer)
            chartContainer.style.cursor = 'pointer'; 
        } else {
            // Jika kursor meninggalkan titik data, kembalikan ke kursor default
            chartContainer.style.cursor = 'default';
        }
    },
    // ============================================================
    
    // FUNGSI UNTUK REDIRECT SAAT DIKLIK
    onClick: (e, elements) => {
        if (elements.length > 0) {
            const firstElement = elements[0];
            const index = firstElement.index;
            
            // Ambil label (tanggal) dari index yang diklik
            const clickedLabel = chartLabels[index];
            const dateFilter = clickedLabel; 

            // Redirect ke halaman daftar order dengan filter tanggal
            const redirectUrl = `{{ route('admin.orders.index') }}?date=${dateFilter}`;
            window.location.href = redirectUrl;
        }
    },
    // Pengaturan interaksi
    interaction: {
        mode: 'index',
        intersect: false,
    },
    hover: {
        mode: 'nearest',
        intersect: true
    }
}
    });
</script>
@endpush