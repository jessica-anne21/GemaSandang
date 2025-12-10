<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        // Kirim data ke view
        return view('customer.cart', compact('cart'));
    }

    /**
     * Menyimpan produk baru ke dalam keranjang (session).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        // Cek apakah produk sudah ada di keranjang
        if (isset($cart[$product->id])) {

            // Dapatkan kuantitas saat ini di keranjang
            $currentQuantityInCart = $cart[$product->id]['quantity'];

            // Cek apakah kuantitas di keranjang SUDAH SAMA DENGAN stok
            if ($currentQuantityInCart >= $product->stok) {
                return redirect()->back()->with('warning', 'Stok produk "' . $product->nama_produk . '" hanya ' . $product->stok . ', Anda tidak bisa menambahkan lagi.');
            } else {
                $cart[$product->id]['quantity']++;
            }

        } else {

            // Cek apakah stok produk masih ada (> 0)
            if ($product->stok < 1) {
                // Jika stok habis, kembalikan dengan pesan error.
                return redirect()->back()->with('error', 'Maaf, stok produk "' . $product->nama_produk . '" sudah habis.');
            } else {
                // Jika stok ada, tambahkan produk ke keranjang dengan kuantitas 1
                $cart[$product->id] = [
                    "name" => $product->nama_produk,
                    "quantity" => 1,
                    "price" => $product->harga,
                    "photo" => $product->foto_produk
                ];
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}