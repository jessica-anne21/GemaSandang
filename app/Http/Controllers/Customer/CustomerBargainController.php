<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bargain;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBargainController extends Controller
{
    public function index()
{
    $bargains = Bargain::with('product')
        ->where('user_id', Auth::id())
        ->latest()
        ->paginate(10);

    return view('customer.bargains.index', compact('bargains'));
}

    /**
     * Menyimpan tawaran baru dari pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'harga_tawaran' => 'required|numeric|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Validasi: Harga tawaran tidak boleh lebih tinggi dari harga asli
        if ($request->harga_tawaran >= $product->harga) {
            return back()->with('error', 'Harga tawaran harus lebih rendah dari harga asli.');
        }

        // Cek apakah sudah ada tawaran pending untuk produk ini
        $existing = Bargain::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->where('status', 'pending')
                            ->first();

        if ($existing) {
            return back()->with('warning', 'Anda sudah memiliki tawaran yang sedang menunggu konfirmasi untuk produk ini.');
        }

        Bargain::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'harga_tawaran' => $request->harga_tawaran,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Tawaran Anda telah dikirim ke Admin. Silakan tunggu konfirmasi.');
    }
}