<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index()
    {
        // Ambil semua order, urutkan dari yang terbaru
        // Eager load 'user' agar tidak N+1 problem saat menampilkan nama pelanggan
        $orders = Order::with('user')->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show($id)
    {
        // Ambil order berdasarkan ID
        // Eager load 'items.product' untuk menampilkan detail barang
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mengupdate status pesanan (dan nomor resi).
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,dikirim,selesai,dibatalkan',
            'nomor_resi' => 'nullable|string|max:255', // Validasi nomor resi
        ]);

        // Update data
        $order->status = $request->status;

        // Jika admin mengisi nomor resi, simpan juga
        if ($request->filled('nomor_resi')) {
            $order->nomor_resi = $request->nomor_resi;
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order->id)
                         ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}