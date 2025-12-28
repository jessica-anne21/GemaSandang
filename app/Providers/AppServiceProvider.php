<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator; // <--- WAJIB DIIMPORT
use App\Models\Category;

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
         * Secara default Laravel menggunakan Tailwind, jadi baris ini wajib ada 
         * agar tombol "Next/Previous" di kelola pesanan (Canvas) tampil rapi.
         */
        Paginator::useBootstrapFive();

        /**
         * 2. View Composer untuk Navbar.
         * Agar variabel $categories selalu tersedia di file navbar tanpa harus 
         * dikirim manual dari setiap Controller.
         */
        View::composer('layouts.partials.navbar', function ($view) {
            $view->with('categories', Category::all());
        });
    }
}