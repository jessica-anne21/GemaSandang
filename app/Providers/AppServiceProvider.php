<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth; // <--- PENTING: Buat cek user login
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Order;   // <--- PENTING: Buat hitung notif order
use App\Models\Bargain; // <--- PENTING: Buat hitung notif tawaran

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 1. Mengatur Pagination agar menggunakan Bootstrap 5.
         */
        Paginator::useBootstrapFive();

        /**
         * 2. View Composer untuk Navbar (layouts.partials.navbar).
         * Disini kita kirim data Kategori DAN data Badge Notifikasi sekaligus.
         */
        View::composer('layouts.partials.navbar', function ($view) {
            
            // A. Ambil Data Kategori (Existing)
            $categories = Category::all();

            // B. Logic Badge Notifikasi (New)
            $badgeOrders = 0;
            $badgeBargains = 0;

            // Cek apakah ada user yang login?
            if (Auth::check()) {
                $userId = Auth::id();

                // Hitung Order yg butuh perhatian (Belum Bayar / Ditolak Admin)
                // Karena kalau ditolak, statusnya balik ke 'menunggu_pembayaran'
                $badgeOrders = Order::where('user_id', $userId)
                    ->where('status', 'menunggu_pembayaran')
                    ->count();

                // Hitung Tawaran yg sudah direspon (Diterima / Ditolak)
                // 'pending' ga dihitung karena user cuma nunggu
                $badgeBargains = Bargain::where('user_id', $userId)
                ->whereIn('status', ['accepted', 'rejected'])
                ->where('is_read', false) // <--- TAMBAHAN PENTING INI
                ->count();
            }

            // Kirim semua variable ke View Navbar
            $view->with('categories', $categories)
                 ->with('badgeOrders', $badgeOrders)
                 ->with('badgeBargains', $badgeBargains);
        });
    }
}