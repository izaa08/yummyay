@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #FF69B4;">Daftar Produk</h2>

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Deskripsi: {{ $product->description }}</p>
                            <p class="card-text">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cartModal{{ $product->id }}">Tambah ke Keranjang</button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="cartModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cartModalLabel{{ $product->id }}">Konfirmasi Pesanan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="quantity">Jumlah</label>
                                            <input type="number" name="quantity" value="1" min="1" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="delivery_date">Hari Pengiriman/pengambilan</label>
                                            <input type="date" name="delivery_date" class="form-control" required min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="delivery_option">Metode Pengambilan</label>
                                            <select name="delivery_option" class="form-control" id="deliveryOption{{ $product->id }}" required onchange="toggleAddress(this, '{{ $product->id }}')">
                                                <option value="pickup">Pickup</option>
                                                <option value="delivery">Delivery</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="addressField{{ $product->id }}" style="display: none;">
                                            <label for="address">Alamat Lengkap</label>
                                            <textarea name="address" class="form-control" placeholder="Masukkan alamat lengkap:(Kota,Nama jalan, No rumah. dll"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="notes">Catatan (Opsional)</label>
                                            <textarea name="notes" class="form-control" placeholder="Masukkan permintaan khusus (misalnya: tambah topping, tambah tulisan, dll)"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleAddress(select, productId) {
            console.log('Toggling address field for productId: ' + productId + ', delivery_option: ' + select.value);
            const addressField = document.getElementById('addressField' + productId);
            const addressInput = addressField.querySelector('textarea[name="address"]');
            if (select.value === 'delivery') {
                addressField.style.display = 'block';
                addressInput.setAttribute('required', 'required');
            } else {
                addressField.style.display = 'none';
                addressInput.removeAttribute('required');
                addressInput.value = ''; // Reset value to empty
            }
        }

        // Pastikan event listener dijalankan saat modal dibuka
        $('.modal').on('shown.bs.modal', function () {
            const productId = $(this).attr('id').replace('cartModal', '');
            console.log('Modal opened for productId: ' + productId);
            const select = document.getElementById('deliveryOption' + productId);
            toggleAddress(select, productId); // Panggil fungsi saat modal dibuka
        });
    </script>
@endsection