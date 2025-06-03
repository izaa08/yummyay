@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4" style="color: #FF69B4;">Daftar Pesanan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Metode Pengambilan</th>
                        <th>Tanggal Pengiriman</th>
                        <th>Nomor WhatsApp</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Diperbarui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->delivery_option == 'pickup' ? 'Diambil' : 'Dikirim' }}</td>
                            <td>{{ $order->delivery_date ?? 'N/A' }}</td>
                            <td>{{ $order->user->whatsapp_number ?? 'N/A' }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->updated_at }}</td>
                            <td>
                                <a href="{{ route('admin.show', $order->id) }}" class="btn btn-info btn-sm">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">Tidak ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
