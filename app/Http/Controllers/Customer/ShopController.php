<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ShopController extends Controller
{
    public function index()
    {
        // Stok > 0 di atas, Stok = 0 di bawah
        $products = Product::with('category')
            ->orderByRaw('stok = 0, created_at DESC')
            ->get();

        $query = null; 
        return view('customer.shop', compact('products', 'query'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::with('category')
            ->where('nama_produk', 'LIKE', "%{$query}%")
            ->orderByRaw('stok = 0, created_at DESC') 
            ->get();

        return view('customer.shop', compact('products', 'query'));
    }

    public function showByCategory(Category $category)
    {
        $products = $category->products()
            ->orderByRaw('stok = 0, created_at DESC')
            ->get();

        return view('customer.shop-by-category', compact('products', 'category'));
    }

    public function show(Product $product)
    {
        return view('customer.product-detail', compact('product'));
    }
}