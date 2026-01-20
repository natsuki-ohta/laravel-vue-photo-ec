<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/order/store', [OrderController::class, 'store']);


Route::middleware([
  EnsureFrontendRequestsAreStateful::class,
  'web',
])->group(function () {
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| Admin API
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::put('/products/update-all', [AdminProductController::class, 'updateAll']);
    Route::post('/products/{product}/image', [AdminProductController::class, 'updateImage']);
    Route::put('/products/{product}', [AdminProductController::class, 'updateStock']);
    Route::post('/products/{product}/delete', [AdminProductController::class,'destroy']);

    Route::get('/order', [AdminOrderController::class, 'index']);
    Route::get('/order/{order}', [AdminOrderController::class, 'show']);
});


/*
|--------------------------------------------------------------------------
| Auth (Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'auth:sanctum',
])->get('/admin/me', function (Request $request) {
    return $request->user();
});

