@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4 text-yummy-pink">Kelola Produk Yummy Ay</h2>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif

        {{-- Form Tambah Produk --}}
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title text-yummy-pink">Tambah Produk Baru</h4>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control-file">
                    </div>
                    <button type="submit" class="btn text-black" style="background-color: #FF69B4;">Tambah Produk</button>
                </form>
            </div>
        </div>

        {{-- Daftar Produk --}}
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-yummy-pink mb-4">Daftar Produk</h4>
                <div class="row">
                    @forelse ($products as $product)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default.jpg') }}" alt="Default Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body d-flex flex-column text-center">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->description }}</p>
                                    <p class="card-text font-weight-bold text-danger">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn bg-yummy-pink text-black mb-2">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">Tidak ada produk yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
