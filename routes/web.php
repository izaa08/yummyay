<?php

  use App\Http\Controllers\AdminController;
  use App\Http\Controllers\OrderController;
  use App\Http\Controllers\CartController;
  use App\Http\Controllers\ProductController;
  use Illuminate\Support\Facades\Route;

  Route::get('/', function () {
    \Log::info('Homepage diakses');
    return view('welcome');
});

  Route::get('/', function () {
      if (auth()->check()) {
          if (auth()->user()->is_admin) {
              return redirect('/admin/dashboard');
          }
          return redirect('/products');
      }
      return view('welcome');
  });

  // Autentikasi default Laravel
  Auth::routes();

  // Rute publik
  Route::get('/products', [ProductController::class, 'index'])->name('products.index');

  // Rute untuk pengguna terautentikasi
  Route::middleware(['auth'])->group(function () {
      Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
      Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
      Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

      // Rute untuk order pengguna
      Route::get('/orders/create/{product_id}', [OrderController::class, 'create'])->name('orders.create');
      Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
      Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
      Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

      // Rute untuk checkout/pembayaran dari cart
      Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
  });

  // Rute untuk admin (middleware admin)
  Route::middleware('admin')->group(function () {
      Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
      Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
      Route::get('/admin/orders/{id}', [AdminController::class, 'show'])->name('admin.show');
      Route::get('/admin/orders/history', [AdminController::class, 'history'])->name('admin.history');
      Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
      Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
      Route::delete('/admin/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
      Route::post('/admin/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
      Route::get('/admin/products/{id}/edit', [AdminController::class, 'edit'])->name('admin.products.edit');
      Route::put('/admin/products/{id}', [AdminController::class, 'update'])->name('admin.products.update');
      Route::patch('/admin/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
      Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history');
  });
