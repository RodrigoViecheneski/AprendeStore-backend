<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});

Route::get('/banners', [BannerController::class, 'getAllBanners']);
Route::get('/products', [ProductController::class, 'getAllProducts']);
