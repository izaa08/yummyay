@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
    <h2 class="text-2xl font-bold mb-4 text-yummy-pink">Pesan Produk</h2>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="mb-4">
            <label for="quantity" class="block">Jumlah</label>
            <input type="number" name="quantity" id="quantity" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label for="delivery_option" class="block">Opsi Pengiriman</label>
            <select name="delivery_option" id="delivery_option" class="w-full border rounded p-2">
                <option value="pickup">Diambil</option>
                <option value="delivery">Diantar</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="delivery_date" class="block">Untuk Hari</label>
            <input type="date" name="delivery_date" id="delivery_date" class="w-full border rounded p-2" min="{{ date('Y-m-d') }}" required>
        </div>

        <button type="submit" class="bg-yummy-pink text-black px-4 py-2 rounded">Pesan</button>
    </form>
@endsection