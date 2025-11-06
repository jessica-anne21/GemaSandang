<?php

namespace App\Http\Controllers;
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
        return view('home', compact('products'));
    }

    /**
     * Menampilkan halaman 'about'.
     */
    public function about()
    {
        return view('about');
    }
}