<?php

use App\Http\Controllers\Api\v1\AddressController;
use App\Http\Controllers\Api\v1\BugController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\FavoriteController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\PaymentController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\ProvinceController;
use App\Http\Controllers\Api\v1\TicketController;
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

    // Payments
    Route::post('pay', [PaymentController::class, 'pay']);
    Route::post('payment-verify', [PaymentController::class, 'verify']);

    // Comments
    Route::get('get-comments', [CommentController::class, 'getComments']);
    Route::post('create-comment', [CommentController::class, 'createComment'])->middleware('auth:sanctum');

    // Bugs
    Route::post('bug-report', [BugController::class, 'bugReport']);

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

        // Addresses
        Route::get('get-addresses', [AddressController::class, 'getAddresses']);
        Route::post('add-address', [AddressController::class, 'addAddress']);
        Route::put('edit-address', [AddressController::class, 'editAddress']);
        Route::delete('delete-address', [AddressController::class, 'deleteAddress']);

        // Tickets
        Route::get('get-tickets', [TicketController::class, 'getTickets']);
        Route::get('get-messages', [TicketController::class, 'getMessages']);
        Route::post('create-ticket', [TicketController::class, 'createTicket']);
        Route::post('send-message', [TicketController::class, 'sendMessage']);

        // Orders
        Route::get('get-orders', [OrderController::class, 'getOrders']);

        // Notifications
        Route::get('get-notifications', [UserController::class, 'getNotifications']);
    });
});

Route::fallback(function (){
    return response()->json('something wrong!', 404);
});
