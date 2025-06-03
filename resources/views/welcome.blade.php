<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yummy Ay Bakery</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background: #fff8f2;
            color: #5c3a21;
        }
        .header {
            text-align: center;
            padding-top: 50px;
        }
        .title {
            font-family: 'Pacifico', cursive;
            font-size: 60px;
            color: #d2691e;
        }
        .tagline {
            font-size: 20px;
            margin-top: -10px;
        }
        .btn {
            background-color: #ffa07a;
            border: none;
            padding: 12px 24px;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            margin: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #ff7f50;
        }
        .image {
            max-width: 400px;
            margin-top: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #a0765c;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Yummy Ay</h1>
        <p class="tagline">Temukan kelezatan dalam setiap gigitan!</p>

        
        <div>
            <a href="{{ url('/products') }}" class="btn">Lihat Produk</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Yummy Ay Bakery. Made with ❤️ and butter.
    </div>
</body>
</html>
