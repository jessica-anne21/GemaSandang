<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Bargain;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja dengan "Satpam" Cerdas.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $changes = false; 
        $removedItems = []; 

        foreach ($cart as $key => $details) {
            
            // 1. Cek apakah item hasil tawar-menawar
            if (isset($details['bargain_id'])) {
                // Cari data tawaran terbaru di Database
                $bargain = \App\Models\Bargain::find($details['bargain_id']);
                
                if (!$bargain || $bargain->status !== 'accepted') {
                    unset($cart[$key]); 
                    $changes = true;
                    $removedItems[] = $details['name'];
                }
            }
            
            // 2. Cek Stok 
            $productDB = \App\Models\Product::find($key);
            if (!$productDB || $productDB->stok < 1) {
                 unset($cart[$key]);
                 $changes = true;
                 $removedItems[] = $details['name'] . ' (Stok Habis)';
            }
        }

        // Jika ada yang dihapus, update session & kasih notif
        if ($changes) {
            session()->put('cart', $cart);
            session()->flash('warning', 'Beberapa item dihapus dari keranjang karena penawaran dibatalkan atau stok habis: ' . implode(', ', $removedItems));
        }

        return view('customer.cart', compact('cart'));
    }

    /**
     * Menyimpan produk baru ke dalam keranjang (Normal Price).
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
            // Cek stok
            if ($cart[$product->id]['quantity'] >= $product->stok) {
                return redirect()->back()->with('warning', 'Stok produk "' . $product->nama_produk . '" mentok, tidak bisa tambah lagi.');
            }
            $cart[$product->id]['quantity']++;
        } else {
            // Cek stok awal
            if ($product->stok < 1) {
                return redirect()->back()->with('error', 'Maaf, stok produk habis.');
            }
            
            // Simpan pakai Product ID sebagai Key
            $cart[$product->id] = [
                "name" => $product->nama_produk,
                "quantity" => 1,
                "price" => $product->harga,
                "photo" => $product->foto_produk,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menyimpan produk hasil nego ke keranjang.
     */
    public function addFromBargain(Request $request)
    {
        $bargain = Bargain::with('product')
            ->where('id', $request->bargain_id)
            ->where('user_id', auth()->id())
            ->where('status', 'accepted')
            ->firstOrFail();

        $cart = session()->get('cart', []);

        // Gunakan ID Produk sebagai Key agar kompatibel dengan sistem checkout
        $cartKey = $bargain->product->id; 

        // Isi cart dengan data bargain
        $cart[$cartKey] = [
            'name' => $bargain->product->nama_produk,
            'quantity' => 1,
            'price' => $bargain->harga_tawaran, 
            'photo' => $bargain->product->foto_produk,
            'is_bargain' => true, 
            'bargain_id' => $bargain->id,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Produk hasil tawar berhasil dimasukkan ke keranjang dengan harga spesial!');
    }

    /**
     * Menghapus item dari keranjang.
     */
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