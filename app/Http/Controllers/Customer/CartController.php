<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Bargain;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $removedItems = [];

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            
            if (!$product || $product->stok < 1) {
                $removedItems[] = $details['name']; 
                unset($cart[$id]); 
            }
        }

        if (!empty($removedItems)) {
            session()->put('cart', $cart);
            $namaProduk = implode(', ', $removedItems);
            
            session()->flash('warning', "Maaf, produk ($namaProduk) baru saja di-checkout pelanggan lain. Stok thrift kami terbatas 1 pcs per item.");
            
            return redirect()->route('cart.index'); 
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