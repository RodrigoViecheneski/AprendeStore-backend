<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

Route::get('/banners', [BannerController::class, 'getAllBanners']);
Route::get('/products', [ProductController::class, 'getAllProducts']);
Route::get('/product/{id}', [ProductController::class, 'getProductById']);
Route::get('/product/{id}/related', [ProductController::class, 'getProductsRelatedById']);
Route::get('/categories/{slug}/metadata', [CategoryController::class, 'getCategoryMetadataBySlug']);
Route::post('/cart/mount', [CartController::class, 'mount']);
Route::get('cart/shipping', [CartController::class, 'shipping']);

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Rotas protegidas por autenticação
    // Exemplo: Route::get('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/addresses', [UserController::class, 'createAddress']);
    Route::get('/user/addresses', [UserController::class, 'getAddresses']);
    Route::post('/cart/finish', [CartController::class, 'finish']);
});
