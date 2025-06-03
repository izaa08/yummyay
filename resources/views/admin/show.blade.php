@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #FF69B4;">Detail Pesanan {{ $order->user ? $order->user->name : 'Pelanggan Tidak Ditemukan' }}</h2>

        <div class="card">
            <div class="card-body">
    
                <p><strong>Nama Produk:</strong> {{ $order->product ? $order->product->name : 'Produk Tidak Ditemukan' }}</p>
                <p><strong>Jumlah:</strong> {{ $order->quantity }}</p>
                <p><strong>Metode Pengambilan/pengantaran:</strong> {{ $order->delivery_option }}</p>
                <p><strong>Tanggal Pengambilan/pengantaran:</strong> {{ $order->delivery_date ?? 'N/A' }}</p>
                <p><strong>Alamat:</strong> {{ $order->address ?? 'N/A' }}</p>
                <p><strong>Catatan:</strong> {{ $order->notes ?? 'Tidak ada catatan' }}</p>
                <p><strong>Nomor WhatsApp:</strong> {{ $order->user ? $order->user->whatsapp_number : 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Dipesan Pada:</strong> {{ $order->created_at }}</p>

                <!-- Form untuk mengubah status -->
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="status">Ubah Status:</label>
                        <select name="status" id="status" class="form-control" style="width: 200px;">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>

                <a href="{{ route('admin.orders') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
@endsection