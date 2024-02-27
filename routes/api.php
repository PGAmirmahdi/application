<?php

use App\Http\Controllers\Api\v1\FavoriteController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\ProvinceController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (){

    // Products
    Route::get('products', [ProductController::class, 'getProducts']);
    Route::get('search-products', [ProductController::class, 'search']);

    // Authorization
    Route::post('register',[UserController::class,'register']);
    Route::post('login',[UserController::class,'login']);
    Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');
    Route::post('send-code',[UserController::class,'sendCode']);

    Route::middleware('auth:sanctum')->group(function (){

        // Profile
        Route::get('get-profile', [UserController::class, 'getProfile']);
        Route::put('edit-profile', [UserController::class, 'editProfile']);

        // Province
        Route::get('get-provinces', [ProvinceController::class, 'getProvinces']);

        // Favorites
        Route::get('get-favorites', [FavoriteController::class, 'getFavorites']);
        Route::post('toggle-favorite', [FavoriteController::class, 'toggleFavorite']);
        Route::post('check-favorite', [FavoriteController::class, 'checkFavorite']);
    });
});

Route::fallback(function (){
    return response()->json('something wrong!', 404);
});
