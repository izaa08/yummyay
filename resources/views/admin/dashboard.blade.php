@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #FF69B4;">Dashboard Admin Yummy Ay</h2>

        <!-- Konten Dashboard -->
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title" style="color: #FF69B4;">Selamat Datang, {{ Auth::user()->name }}!</h4>
                <p class="card-text">Gunakan menu di atas untuk mengelola pesanan dan produk.</p>
            </div>
        </div>
    </div>
@endsection