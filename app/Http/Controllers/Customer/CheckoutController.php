<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart');
        if (!$cart || count($cart) == 0) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong.');
        }
        return view('customer.checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {        
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:500',
            'kurir' => 'required|string',
            'ongkir' => 'required|numeric',
            'nomor_hp' => 'required|string|max:15',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $cart = session()->get('cart');
        
        if (!$cart) {
            return redirect()->route('shop');
        }

        DB::beginTransaction();

        try {
            // 1. Hitung Total (Cukup sekali loop)
            $subtotal = 0;
            foreach ($cart as $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }
            $grandTotal = $subtotal + $request->ongkir;

            // 2. Buat Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $grandTotal,
                'status' => 'menunggu_pembayaran', // Status awal yang lebih jelas
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'kurir' => $request->kurir,
                'ongkir' => $request->ongkir,
                'nomor_hp' => $request->nomor_hp, 
                // 'catatan' => $request->catatan,
            ]);

            // 3. Loop Barang
            foreach ($cart as $id => $details) {
                // Lock produk biar aman dari rebutan stok
                $product = Product::lockForUpdate()->find($id); 

                if (!$product || $product->stok < $details['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Maaf, produk "' . $details['name'] . '" baru saja habis terjual.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'kuantitas' => $details['quantity'],
                    'harga_saat_beli' => $details['price'],
                ]);

                $product->decrement('stok', $details['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        return view('customer.checkout.success', compact('order'));
    }

    public function uploadProof(Request $request, $orderId)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('payment_proofs', 'public');
            
            $order->update([
                'bukti_bayar' => $path,
                'status' => 'menunggu_konfirmasi' // Update status otomatis
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi Admin.');
    }
}