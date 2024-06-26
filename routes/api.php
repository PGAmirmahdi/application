<?php

use App\Http\Controllers\Api\v1\AddressController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\BugController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\DeliveryDayController;
use App\Http\Controllers\Api\v1\FavoriteController;
use App\Http\Controllers\Api\v1\GuideVideosController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\PaymentController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\ProvinceController;
use App\Http\Controllers\Api\v1\ReturnController;
use App\Http\Controllers\Api\v1\TicketController;
use App\Http\Controllers\Api\v1\UpdateController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\PanelController;
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
    Route::get('filter-products', [ProductController::class, 'filter']);

    // Authorization
    Route::post('register',[UserController::class,'register']);
    Route::post('login',[UserController::class,'login']);
    Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');
    Route::post('send-code',[UserController::class,'sendCode']);

    // Comments
    Route::get('get-comments', [CommentController::class, 'getComments']);
    Route::post('create-comment', [CommentController::class, 'createComment'])->middleware('auth:sanctum');

    // Categories
    Route::get('get-categories', [CategoryController::class, 'getCategories']);
    Route::get('get-category-children', [CategoryController::class, 'getChildren']);
    Route::get('get-category-products', [CategoryController::class, 'getProducts']);

    // Updates
    Route::get('get-update', [UpdateController::class, 'getUpdate']);

    // Bugs
    Route::post('bug-report', [BugController::class, 'bugReport']);

    // Banners
    Route::get('get-banners', [BannerController::class, 'getBanners']);
    Route::get('get-banners_mid', [BannerController::class, 'getBanners_mid']);

    //    Guide Videos
    Route::get('getGuideVideos', [GuideVideosController::class, 'getGuideVideos']);

    //    Coupone
    Route::get('getGuideVideos', [GuideVideosController::class, 'getGuideVideos']);

    // Payments
    Route::post('pay', [PaymentController::class, 'pay']);
    Route::post('payment-verify', [PaymentController::class, 'verify']);

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
        Route::get('get-order', [OrderController::class, 'getOrder']);

        // Notifications
        Route::get('get-notifications', [UserController::class, 'getNotifications']);
        Route::put('read-notification', [UserController::class, 'readNotification']);

        // FCM Token
        Route::post('save-token', [PanelController::class, 'saveFCMToken']);

        // Return
        Route::get('get-returns', [ReturnController::class, 'getReturns']);
        Route::get('get-order-items', [ReturnController::class, 'getOrderItems']);
        Route::post('create-return', [ReturnController::class, 'createReturn']);
    });
    // Delivery Days
    Route::get('get-delivery-days', [DeliveryDayController::class, 'getDeliveryDays']);
    Route::get('delivery-day/is-selected', [DeliveryDayController::class, 'isSelected']);
    Route::post('select-day', [DeliveryDayController::class, 'toggleDay']);
});

Route::fallback(function (){
    return response()->json('something wrong!', 404);
});
