<?php

namespace App\Http\Controllers;

use App\Models\Product; // 1. Import Model Product
use Illuminate\Http\Request;
use App\Models\Category;

class ShopController extends Controller
{
    /**
     * Menampilkan halaman shop dengan semua produk.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua data produk dari database
        // - 'with('category')' untuk mengambil data relasi kategori 
        // - 'latest()' untuk mengurutkan produk dari yang paling baru ditambahkan
        $products = Product::with('category')->latest()->get();

        // Kirim data variabel $products ke view 'shop.blade.php'
        return view('shop', compact('products'));
    }

    public function showByCategory(Category $category)
    {

        // Ambil semua produk yang memiliki category_id yang sama
        $products = $category->products()->latest()->get();

        // Kirim data ke view baru
        return view('shop-by-category', compact('products', 'category'));
    }

    public function show(Product $product)
    {
        return view('product-detail', compact('product'));
    }
}