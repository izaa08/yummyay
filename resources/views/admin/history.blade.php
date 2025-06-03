@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4" style="color: #FF69B4;">Riwayat Pesanan Yummy Ay</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h4 class="card-title" style="color: #FF69B4;">Riwayat Semua Pesanan</h4>

            @forelse ($orders as $order)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pesanan #{{ $order->id }}</h5>
                        <p>Pengguna: {{ $order->user->name ?? 'Tidak Diketahui' }} ({{ $order->user->email ?? 'Email Tidak Tersedia' }})</p>
                        <p>Produk: {{ $order->product->name ?? 'Tidak Diketahui' }}</p>
                        <p>Jumlah: {{ $order->quantity }}</p>
                        <p>Opsi Pengiriman: {{ $order->delivery_option == 'pickup' ? 'Diambil' : 'Dikirim' }}</p>
                        <p>Tanggal Pengiriman: {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d-m-Y') : 'N/A' }}</p>
                        <p>Status: {{ ucfirst($order->status) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center">Tidak ada riwayat pesanan.</p>
            @endforelse

            <h4 class="card-title mt-4" style="color: #FF69B4;">Total Pesanan Per User dan Tanggal</h4>
            @forelse ($totalsByUserAndDate as $entry)
                @if ($entry['total'] > 0)
                    <div class="card mt-3">
                        <div class="card-body">
                            <p><strong>Total untuk {{ $entry['user_name'] }} pada {{ \Carbon\Carbon::parse($entry['date'])->format('d-m-Y') }}: Rp {{ number_format($entry['total'], 0, ',', '.') }}</strong></p>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-center">Belum ada total pesanan yang dapat ditampilkan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
