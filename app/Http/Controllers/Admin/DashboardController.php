<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // 1. Import Model Product
use App\Models\Order;   // 2. Import Model Order
use App\Models\User;    // 3. Import Model User

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data statistik.
     */
    public function index()
    {
        // 1. Hitung Total Produk
        $totalProducts = Product::count();

        // 2. Hitung Pesanan Baru (asumsi status 'pending')
        $newOrders = Order::where('status', 'pending')->count();

        // 3. Hitung Total Pelanggan (yang rolenya 'customer')
        $totalCustomers = User::where('role', 'customer')->count();

        // 4. Ambil 5 Pesanan Terbaru untuk ditampilkan di tabel
        $recentOrders = Order::with('user') // Mengambil data user (untuk nama)
                             ->latest()    // Diurutkan dari yang terbaru
                             ->take(5)     // Ambil 5 saja
                             ->get();

        // 5. Kirim semua data ini ke view
        return view('admin.dashboard', compact(
            'totalProducts',
            'newOrders',
            'totalCustomers',
            'recentOrders'
        ));
    }
}