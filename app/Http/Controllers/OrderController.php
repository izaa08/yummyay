<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        foreach ($cartItems as $item) {
            Order::create([
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'delivery_option' => $item->delivery_option,
                'delivery_date' => $item->delivery_date,
                'status' => 'pending',
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $whatsappMessage = "Halo, saya ingin membayar pesanan dengan total Rp " . number_format($total, 0, ',', '.') . ". Detail pesanan: " . $cartItems->pluck('product.name')->implode(', ') . ". Tanggal Pengiriman: " . $cartItems->pluck('delivery_date')->implode(', ') . ". Metode: " . $cartItems->pluck('delivery_option')->implode(', ') . ". Alamat: " . ($cartItems->where('delivery_option', 'delivery')->pluck('address')->implode(', ') ?: 'N/A');

        return redirect()->away("https://wa.me/" . Auth::user()->whatsapp_number . "?text=" . urlencode($whatsappMessage));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereDate('delivery_date', '>=', now()->subDay())
            ->with(['product', 'user'])
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereDate('delivery_date', '<', now()->subDay())
            ->with(['product', 'user'])
            ->get();

        return view('orders.history', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'delivery_option' => 'required|in:pickup,delivery',
            'delivery_date' => 'required|date',
        ]);

        Order::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'delivery_option' => $validated['delivery_option'],
            'delivery_date' => $validated['delivery_date'],
            'status' => 'pending',
        ]);

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat.');
    }
}
