@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #FF69B4;">Riwayat Pesanan</h2>

        @if ($orders->isEmpty())
            <p class="text-center">Belum ada riwayat pesanan.</p>
        @else
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Metode Pengambilan</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Status</th>
                                <th>Dibuat Pada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->product ? $order->product->name : 'Produk Tidak Ditemukan' }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->delivery_option }}</td>
                                    <td>{{ $order->delivery_date ?? 'N/A' }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection