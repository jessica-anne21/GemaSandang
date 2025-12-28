<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CancelUnpaidOrders extends Command
{
    /**
     * Nama perintah yang dijalankan di terminal.
     * Gunakan: php artisan orders:cancel-unpaid
     */
    protected $signature = 'orders:cancel-unpaid';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Batalkan pesanan yang belum dibayar setelah batas waktu (1 menit untuk testing)';

    public function handle()
    {
        // SETTING WAKTU: 1 menit untuk testing.
        // Nanti kalau sudah oke, ganti menjadi subHours(24)
        $expiredTime = Carbon::now()->subMinutes(1);

        // Ambil pesanan yang masih 'menunggu_pembayaran' dan sudah expired
        $orders = Order::where('status', 'menunggu_pembayaran')
                       ->where('created_at', '<', $expiredTime)
                       ->get();

        if ($orders->isEmpty()) {
            $this->info("Tidak ada pesanan yang kadaluwarsa saat ini.");
            return;
        }

        foreach ($orders as $order) {
            // Gunakan Transaction agar perubahan status & stok aman (all or nothing)
            DB::transaction(function () use ($order) {
                
                // 1. Kembalikan stok produk
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        // Menggunakan 'kuantitas' sesuai file OrderItem.php kamu
                        $product->increment('stok', $item->kuantitas);
                    }
                }

                // 2. UPDATE STATUS ORDER & CATATAN ADMIN
                $order->update([
                    'status' => 'dibatalkan',
                    'catatan_admin' => 'Dibatalkan otomatis oleh sistem (Melewati batas waktu pembayaran).' 
                ]);

            });
            
            $this->info("Order ID #{$order->id} berhasil dibatalkan, stok dikembalikan & catatan disimpan.");
        }

        $this->info("Proses selesai.");
    }
}