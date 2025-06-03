<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // Jika user sudah login, cek apakah dia admin
            if (Auth::user()->is_admin) {
                return redirect('/admin/orders');
            }
            // Jika bukan admin, arahkan ke halaman produk
            return redirect('/products');
        }

        return $next($request);
    }
}