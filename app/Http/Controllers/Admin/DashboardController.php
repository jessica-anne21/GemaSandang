<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Order;   
use App\Models\User;    
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data statistik.
     */
    public function index()
    {
        // --- STATISTIK ---
        $totalProducts = Product::count();

        $newOrders = Order::where('status', 'pending')->count();

        $totalCustomers = User::where('role', 'customer')->count();

        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(5)
                             ->get();

        // --- PENJUALAN HARIAN 30 HARI ---
        $salesPerDay = Order::selectRaw('DATE(created_at) as date, SUM(total_harga) as total')
            ->where('status', 'selesai') // optional: hanya penjualan selesai
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->take(30)
            ->get();

        // Siapkan data untuk Chart.js
        $labels = $salesPerDay->pluck('date');
        $data   = $salesPerDay->pluck('total');
        $totalRevenue = Order::where('status', 'selesai')->sum('total_harga');

        return view('admin.dashboard', compact(
            'totalProducts',
            'newOrders',
            'totalCustomers',
            'recentOrders',
            'labels',
            'data',
            'totalRevenue'
        ));
    }
}
