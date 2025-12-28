<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan dengan opsi filter tanggal.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Logika FILTER TANGGAL dari Dashboard Chart
        if ($request->has('date')) {
            $date = $request->date;
            // Filter pesanan yang dibuat (created_at) pada tanggal spesifik (YYYY-MM-DD)
            $query->whereDate('created_at', $date);
        
        }

        // Ambil semua pesanan, urutkan dari yang terbaru dan terapkan paginasi
        $orders = $query->paginate(10); 

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show($id)
    {
        // Ambil pesanan berdasarkan ID beserta relasi user dan items (produk)
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mengupdate status pesanan dan nomor resi.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi input
        $request->validate([
            // Memperbaiki status sesuai yang digunakan di aplikasi
            'status' => 'required|in:menunggu_pembayaran,menunggu_konfirmasi,dikirim,selesai,dibatalkan',
            'nomor_resi' => 'nullable|string|max:255',
        ]);

        // Update status
        $order->status = $request->status;

        // Update nomor resi jika ada input (terutama jika status 'dikirim')
        if ($request->filled('nomor_resi')) {
            $order->nomor_resi = $request->nomor_resi;
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order->id)
                         ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function rejectPayment(Request $request, Order $order)
{
    $request->validate([
        'catatan_admin' => 'required|string|max:255'
    ]);

    // Hapus bukti bayar lama dari storage (Opsional, biar hemat memori)
    if ($order->bukti_bayar) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($order->bukti_bayar);
    }

    $order->update([
        'status' => 'menunggu_pembayaran', // PENTING: Balikin status biar form upload muncul lagi
        'bukti_bayar' => null, // Kosongin biar kedetect belum bayar
        'catatan_admin' => $request->catatan_admin // Simpan alasan penolakan
    ]);

    return redirect()->back()->with('success', 'Pembayaran ditolak. Notifikasi dikirim ke customer.');
}
    
}