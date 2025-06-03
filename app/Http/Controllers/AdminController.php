<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

   public function orders()
{
    $today = now()->toDateString();

    $orders = Order::with(['product', 'user'])
        ->where('delivery_date', '>=', $today)
        ->get();

    return view('admin.orders', compact('orders'));
}


    public function show($id)
    {
        try {
            $order = Order::with(['product', 'user'])->findOrFail($id);
            return view('admin.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error in AdminController@show: ' . $e->getMessage());
            return redirect()->route('admin.orders')->with('error', 'Gagal menampilkan detail pesanan.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $request->validate([
                'status' => 'required|in:pending,paid',
            ]);

            $order->status = $request->status;
            $order->save();

            Log::info('Order status updated: Order ID ' . $id . ', New Status: ' . $request->status);

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status pesanan. Error: ' . $e->getMessage());
        }
    }

   public function history()
{
    $today = now()->toDateString();

    $orders = Order::with(['product', 'user'])
        ->where('delivery_date', '<', $today)
        ->get();

    // Hitung total pesanan per user dan tanggal
    $totalsByUserAndDate = [];
    foreach ($orders as $order) {
        $userName = $order->user ? $order->user->name : 'Unknown User';
        $date = $order->delivery_date;
        $key = $userName . '|' . $date;

        if (!isset($totalsByUserAndDate[$key])) {
            $totalsByUserAndDate[$key] = [
                'user_name' => $userName,
                'date' => $date,
                'total' => 0,
            ];
        }

        $totalPrice = $order->product ? ($order->product->price * $order->quantity) : 0;
        $totalsByUserAndDate[$key]['total'] += $totalPrice;
    }

    $totalsByUserAndDate = array_values($totalsByUserAndDate);

    return view('admin.history', compact('orders', 'totalsByUserAndDate'));
}


    public function products()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    public function create()
    {
        return view('admin.products');
    }

    public function storeProduct(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only(['name', 'description', 'price']);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $data['image'] = $imagePath;
            }

            Product::create($data);

            return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan produk. Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only(['name', 'description', 'price']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
                $data['image'] = $imagePath;
            }

            $product->update($data);

            return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui produk. Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus produk. Error: ' . $e->getMessage());
        }
    }
}
