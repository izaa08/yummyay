@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fef1e6;
    }
</style>

<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="w-100" style="max-width: 460px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #d2691e;">Register</h2>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name"
                    class="form-control rounded-3 @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="whatsapp_number" class="form-label">Nomor WhatsApp</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number"
                    class="form-control rounded-3 @error('whatsapp_number') is-invalid @enderror"
                    value="{{ old('whatsapp_number') }}" required placeholder="Contoh: 08123456789">
                @error('whatsapp_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                    class="form-control rounded-3 @error('password') is-invalid @enderror"
                    required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password-confirm"
                    class="form-control rounded-3" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn" style="background-color: #ff9966; color: white;">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
