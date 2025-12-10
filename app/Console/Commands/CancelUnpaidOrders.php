<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Batalkan pesanan yang belum dibayar setelah 10 menit';

    public function handle()
    {
        // Cari pesanan yang expired (lebih dari 10 menit lalu)
        $expiredTime = Carbon::now()->subMinutes(10);

        $orders = Order::where('status', 'menunggu_pembayaran')
                       ->where('created_at', '<', $expiredTime)
                       ->get();

        foreach ($orders as $order) {
            // Kembalikan stok produk
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stok', $item->kuantitas);
                }
            }

            // Update status jadi dibatalkan
            $order->update(['status' => 'dibatalkan']);
            
            $this->info("Order ID {$order->id} dibatalkan.");
        }
    }
}