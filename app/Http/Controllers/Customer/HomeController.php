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
        // 2. Ambil 8 produk paling baru
        $products = Product::with('category')->latest()->take(8)->get();

        // 3. Kirim data produk ke view
        return view('customer.home', compact('products'));
    }

    /**
     * Menampilkan halaman 'about'.
     */
    public function about()
    {
        return view('customer.about');
    }
}