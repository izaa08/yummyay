<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $productId)
    {
        try {
            Log::info('Adding product to cart for user_id: ' . Auth::id() . ', product_id: ' . $productId);
            Log::info('Request data: ' . json_encode($request->all()));

            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:1',
                'delivery_date' => 'required|date',
                'delivery_option' => 'required|in:pickup,delivery',
                'address' => 'required_if:delivery_option,delivery|nullable|string',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed: ' . json_encode($validator->errors()->toArray()));
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $product = Product::findOrFail($productId);
            Log::info('Product found: ' . $product->name);

            $cart = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

            $address = $request->delivery_option === 'delivery' ? $request->address : null;

            if ($cart) {
                $cart->quantity += $request->quantity;
                $cart->delivery_date = $request->delivery_date;
                $cart->delivery_option = $request->delivery_option;
                $cart->address = $address;
                Log::info('Incrementing quantity for product_id: ' . $productId . ', new quantity: ' . $cart->quantity);
            } else {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $request->quantity,
                    'delivery_date' => $request->delivery_date,
                    'delivery_option' => $request->delivery_option,
                    'address' => $address,
                    'notes' => $request->notes,
                ]);
                Log::info('Created new cart entry for product_id: ' . $productId);
            }

            $cart->save();
            Log::info('Cart saved: ' . json_encode($cart->toArray()));

            return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan produk ke keranjang. Coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function checkout(Request $request)
    {
        try {
            Log::info('Checkout process started for user_id: ' . Auth::id());

            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

            if ($cartItems->isEmpty()) {
                Log::warning('Cart is empty for user_id: ' . Auth::id());
                return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
            }

            // Validasi data cart
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    Log::error('Product not found for product_id: ' . $item->product_id . ' in cart item ID: ' . $item->id);
                    throw new \Exception('Produk tidak ditemukan untuk item di keranjang.');
                }
                if (!$item->delivery_date) {
                    Log::error('Delivery date is missing for cart item ID: ' . $item->id);
                    throw new \Exception('Tanggal pengiriman tidak ditemukan.');
                }
                if (!$item->delivery_option) {
                    Log::error('Delivery option is missing for cart item ID: ' . $item->id);
                    throw new \Exception('Metode pengiriman tidak ditemukan.');
                }
            }

            // Hitung total
            $total = $cartItems->sum(function ($item) {
                Log::info('Calculating price for product_id: ' . $item->product_id . ', price: ' . $item->product->price . ', quantity: ' . $item->quantity);
                return $item->product->price * $item->quantity;
            });
            Log::info('Total calculated: Rp ' . number_format($total, 0, ',', '.'));

            // Pindahkan data dari cart ke orders
            foreach ($cartItems as $item) {
                $orderData = [
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'delivery_option' => $item->delivery_option,
                    'delivery_date' => $item->delivery_date,
                    'address' => $item->address,
                    'notes' => $item->notes,
                    'status' => 'pending',
                ];
                Log::info('Creating order with data: ' . json_encode($orderData));
                Order::create($orderData);
                Log::info('Order created for product_id: ' . $item->product_id);
            }

            // Hapus semua item dari cart setelah checkout
            Cart::where('user_id', Auth::id())->delete();
            Log::info('Cart cleared for user_id: ' . Auth::id());

            // Buat pesan WhatsApp
            $whatsappNumber = Auth::user()->whatsapp_number;
            Log::info('Using WhatsApp number: ' . $whatsappNumber);
            if (!$whatsappNumber) {
                Log::error('Whatsapp number is empty for user_id: ' . Auth::id());
                throw new \Exception('Nomor WhatsApp tidak ditemukan.');
            }

            $whatsappMessage = "Halo, saya ingin membayar pesanan dengan total Rp " . number_format($total, 0, ',', '.') . ". Detail pesanan: " . $cartItems->pluck('product.name')->implode(', ') . ". Tanggal Pengiriman: " . $cartItems->pluck('delivery_date')->implode(', ') . ". Metode: " . $cartItems->pluck('delivery_option')->implode(', ') . ". Alamat: " . ($cartItems->where('delivery_option', 'delivery')->pluck('address')->implode(', ') ?: 'N/A') . ". Catatan: " . ($cartItems->pluck('notes')->filter()->implode(', ') ?: 'Tidak ada catatan');
            $whatsappUrl = "https://wa.me/" . $whatsappNumber . "?text=" . urlencode($whatsappMessage);

            Log::info('Redirecting to WhatsApp: ' . $whatsappUrl);

            // Redirect ke WhatsApp
            return redirect()->away($whatsappUrl)->with('success', 'Pembayaran berhasil, pesanan telah dibuat! Kembali ke /orders setelah konfirmasi.');
        } catch (\Exception $e) {
            Log::error('Error during checkout: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pembayaran. Coba lagi. Error: ' . $e->getMessage());
        }
    }
}