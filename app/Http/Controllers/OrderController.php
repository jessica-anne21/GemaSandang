<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil pesanan milik pengguna yang sedang login, diurutkan dari yang terbaru
        $orders = Auth::user()->orders()->latest()->get();

        return view('orders.index', compact('orders'));
    }
}