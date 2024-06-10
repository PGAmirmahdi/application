<?php

use App\Http\Controllers\BackToAppController;
use App\Http\Controllers\Panel\BannerController;
use App\Http\Controllers\Panel\BugController;
use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\Panel\CommentController;
use App\Http\Controllers\Panel\DeliveryDayController;
use App\Http\Controllers\Panel\OrderController;
use App\Http\Controllers\Panel\PaymentController;
use App\Http\Controllers\Panel\ProductController;
use App\Http\Controllers\Panel\ReturnController;
use App\Http\Controllers\Panel\TicketController;
use App\Http\Controllers\Panel\UpdateController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\PanelController;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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

Route::get('test', function (){

});

Route::middleware(['auth','admin'])->prefix('/panel')->group(function (){
    Route::match(['get','post'],'/', [PanelController::class, 'index'])->name('panel');

    // Users
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('get-addresses/{user}', [UserController::class, 'getAddresses']);

    // Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('search/products', [ProductController::class, 'search'])->name('products.search');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Tickets
    Route::resource('tickets',TicketController::class)->except(['delete']);
    Route::get('change-status-ticket/{ticket}',[TicketController::class, 'changeStatus'])->name('ticket.changeStatus');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('search/orders', [OrderController::class, 'search'])->name('orders.search');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('order-change-status', [OrderController::class, 'changeStatus'])->name('orders.changeStatus');
    Route::post('order-cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Payments
    Route::get('payments',[PaymentController::class, 'index'])->name('payments.index');
    Route::get('search/payments',[PaymentController::class, 'search'])->name('payments.search');

    // Comments
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('comment-change-status', [CommentController::class, 'changeStatus'])->name('comments.changeStatus');

    // Delivery Days
    Route::get('delivery-days', [DeliveryDayController::class, 'index'])->name('delivery-days.index');
    Route::post('select-day', [DeliveryDayController::class, 'toggleDay'])->name('select-day');

    // Bugs
    Route::get('bugs', [BugController::class, 'index'])->name('bugs.index');

    // Banners
    Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('banner/upload', [BannerController::class, 'upload'])->name('banners.upload');
    Route::post('banner_mid/upload_mid', [BannerController::class, 'upload_mid'])->name('banners_mid.upload_mid');
    // Returns
    Route::get('returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('returns/{return}', [ReturnController::class, 'show'])->name('returns.show');
    Route::post('return/change-status/{return}', [ReturnController::class, 'changeStatus'])->name('returns.change-status');

    // Updates
    Route::resource('updates', UpdateController::class)->except(['show']);
});
// Back To the application
Route::get('BackToApp', function (Request $request) {
    $status=$request->query('Status');
    $authority=$request->query('Authority');
    return view('panel.payments.BackToApp',['Status'=>$status,'Authority'=>$authority]);
})->name("BackToApp");


Auth::routes(['register' => false, 'reset' => false, 'confirm' => false]);

Route::fallback(function (){
    abort(404);
});
