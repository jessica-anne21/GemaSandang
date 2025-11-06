<?php

namespace App\Http\Controllers;

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
        return view('cart', compact('cart'));
    }

    /**
     * Menyimpan produk baru ke dalam keranjang (session).
     */
    public function store(Request $request)
    {
        // 1. Validasi request
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // 2. Ambil data produk dari database 
        $product = Product::findOrFail($request->product_id);

        // 3. Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // 4. Cek apakah produk sudah ada di keranjang
        if (isset($cart[$product->id])) {

            // a. Dapatkan kuantitas saat ini di keranjang
            $currentQuantityInCart = $cart[$product->id]['quantity'];

            // b. Cek apakah kuantitas di keranjang SUDAH SAMA DENGAN stok
            if ($currentQuantityInCart >= $product->stok) {
                return redirect()->back()->with('warning', 'Stok produk "' . $product->nama_produk . '" hanya ' . $product->stok . ', Anda tidak bisa menambahkan lagi.');
            } else {
                $cart[$product->id]['quantity']++;
            }

        } else {

            // a. Cek apakah stok produk masih ada (> 0)
            if ($product->stok < 1) {
                // b. Jika stok habis, kembalikan dengan pesan error.
                return redirect()->back()->with('error', 'Maaf, stok produk "' . $product->nama_produk . '" sudah habis.');
            } else {
                // c. Jika stok ada, tambahkan produk ke keranjang dengan kuantitas 1
                $cart[$product->id] = [
                    "name" => $product->nama_produk,
                    "quantity" => 1,
                    "price" => $product->harga,
                    "photo" => $product->foto_produk
                ];
            }
        }

        // 5. Simpan kembali array cart ke dalam session
        session()->put('cart', $cart);

        // 6. Redirect kembali dengan pesan sukses 
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        // 1. Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // 2. Cek apakah produk dengan $id tersebut ada di keranjang
        if(isset($cart[$id])) {
            // 3. Hapus produk dari array keranjang
            unset($cart[$id]);

            // 4. Simpan kembali keranjang yang sudah di-update ke session
            session()->put('cart', $cart);
        }

        // 5. Redirect kembali ke halaman keranjang dengan pesan sukses
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}