@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #FF69B4;">Edit Produk Yummy Ay</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block">Nama Produk</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block">Deskripsi</label>
                        <textarea name="description" id="description" class="w-full border rounded p-2" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block">Harga</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" class="w-full border rounded p-2" step="0.01" required>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block">Gambar (opsional, upload baru untuk ganti)</label>
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 mb-2">
                        @endif
                        <input type="file" name="image" id="image" class="w-full border rounded p-2">
                    </div>

                    <button type="submit" class="bg-yummy-pink text-black px-4 py-2 rounded">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection