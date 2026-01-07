<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage; 
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
        // 1. Validasi Input
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:500',
            'kurir' => 'required|string',
            'ongkir' => 'required|numeric',
            'nomor_hp' => 'required|string|max:15',
            'catatan_customer' => 'nullable|string|max:1000', 
        ]);

        $cart = session()->get('cart');
        
        if (!$cart) {
            return redirect()->route('shop');
        }

        $user = Auth::user();
        if ($user->alamat !== $request->alamat_pengiriman || $user->nomor_hp !== $request->nomor_hp) {
            $user->update([
                'alamat' => $request->alamat_pengiriman,
                'nomor_hp' => $request->nomor_hp
            ]);
        }

        DB::beginTransaction();

        try {
            // 2. Hitung Total 
            $subtotal = 0;
            foreach ($cart as $details) {
                $subtotal += $details['price'] * $details['quantity'];
            }
            $grandTotal = $subtotal + $request->ongkir;

            // 3. Buat Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $grandTotal,
                'status' => 'menunggu_pembayaran', 
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'kurir' => $request->kurir,
                'ongkir' => $request->ongkir,
                'nomor_hp' => $request->nomor_hp, 
                'catatan_customer' => $request->catatan_customer, 
            ]);

            // 4. Loop Barang & Kurangi Stok
            foreach ($cart as $id => $details) {
                // Lock produk agar aman dari rebutan stok (Race Condition)
                $product = Product::lockForUpdate()->find($id); 

                // Cek ketersediaan stok
                if (!$product || $product->stok < $details['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Maaf, produk "' . $details['name'] . '" baru saja habis terjual atau stok tidak cukup.');
                }

                // Simpan ke OrderItem
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'kuantitas' => $details['quantity'],
                    'harga_saat_beli' => $details['price'], 
                ]);

                // Kurangi Stok
                $product->decrement('stok', $details['quantity']);
            }

            DB::commit();
            
            // Hapus Keranjang setelah sukses
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

    /**
     * Update: Upload bukti pembayaran langsung ke folder publik
     */
    public function uploadProof(Request $request, $orderId)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_bayar.required' => 'Waduh, bukti bayarnya jangan lupa diupload ya!',
            'bukti_bayar.image'    => 'Format gambar harus jpeg, png, atau jpg.',
            'bukti_bayar.max'      => 'Ukuran foto terlalu besar, maksimal 2MB ya.',
        ]);

        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        if ($request->hasFile('bukti_bayar')) {
            if ($order->bukti_bayar) {
                Storage::disk('public')->delete($order->bukti_bayar);
            }

            // Simpan file baru ke folder public/payment_proofs
            $path = $request->file('bukti_bayar')->store('payment_proofs', 'public');
            
            $order->update([
                'bukti_bayar' => $path,
                'status' => 'menunggu_konfirmasi' 
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi Admin.');
    }
}