<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (home page).
     */
    public function index()
{
    // Hanya ambil 8 produk terbaru yang MASIH ADA STOKNYA
    $products = \App\Models\Product::with('category')
        ->where('stok', '>', 0)
        ->latest()
        ->take(8)
        ->get();

    return view('customer.home', compact('products'));
}

    /**
     * Menampilkan halaman 'about'.
     */
    public function about()
    {
        return view('customer.about');
    }

    /**
     * Menampilkan halaman kontak.
     */
    public function contact()
    {
        return view('customer.contact');
    }
}