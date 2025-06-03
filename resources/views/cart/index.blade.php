@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #FF69B4;">Keranjang Saya</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <p class="text-center">Keranjang kosong.</p>
        @else
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Metode Pengambilan</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->delivery_option }}</td>
                                    <td>{{ $item->delivery_date }}</td>
                                    <td>{{ $item->address ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-right mt-3">
                        <h4><strong>Total Harga: Rp {{ number_format($total, 0, ',', '.') }}</strong></h4>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">Bayar</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection