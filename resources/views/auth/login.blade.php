@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fef1e6; /* warna dari halaman welcome */
    }
</style>

<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="w-100" style="max-width: 400px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #d2691e;">Login</h2>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
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



            <div class="d-grid">
                <button type="submit" class="btn" style="background-color: #ff9966; color: white;">
                    Login
                </button>
            </div>

           
        </form>
    </div>
</div>
@endsection
