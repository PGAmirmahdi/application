<?php

use App\Http\Controllers\Panel\ProductController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()){
        return redirect()->to('/panel');
    }
    return view('auth.login');
});

Route::middleware(['auth','admin'])->prefix('/panel')->group(function (){
    Route::match(['get','post'],'/', [PanelController::class, 'index'])->name('panel');

    // Users
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('get-addresses/{user}', [UserController::class, 'getAddresses']);

    // Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('search/products', [ProductController::class, 'search'])->name('products.search');
});

Auth::routes(['register' => false, 'reset' => false, 'confirm' => false]);

Route::fallback(function (){
    abort(404);
});
