<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil pesanan milik pengguna yang sedang login, diurutkan dari yang terbaru
        $orders = Auth::user()->orders()->latest()->get();

        return view('customer.orders.index', compact('orders'));
    }
}