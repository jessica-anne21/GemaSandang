<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $products = Product::with('category')->latest()->get();
        $query = null; 
        return view('customer.shop', compact('products', 'query'));
    }

    public function showByCategory(Category $category)
    {

        // Ambil semua produk yang memiliki category_id yang sama
        $products = $category->products()->latest()->get();

        // Kirim data ke view baru
        return view('customer.shop-by-category', compact('products', 'category'));
    }

    public function show(Product $product)
    {
        return view('customer.product-detail', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::with('category')
            ->where('nama_produk', 'LIKE', "%{$query}%")
            ->latest()
            ->get();

        return view('customer.shop', compact('products', 'query'));
    }
}