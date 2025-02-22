<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UploadImagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('/getdiscount', [UserController::class, 'getdiscount'])->name('getdiscount');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/trending', [UploadImagesController::class, 'trending'])->name('trending');
Route::post('/add-user', [UserController::class, 'store'])->name('users.store');


Route::middleware(['auth.user'])->group(function () {

    Route::get('/add-user', [UserController::class, 'index'])->name('add-user');

    Route::get('/user-count', [UserController::class, 'count'])->name('users.count');

    //user list
    Route::get('/user-list', [UserController::class, 'userlist'])->name('user-list');
    Route::get('/get-users', [UserController::class, 'gerusers'])->name('get-users');

    Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser']);
    Route::put('/users/update/{id}', [UserController::class, 'update']);

    //UPLOAD IMAGE


    Route::get('/upload-catagoties', [UploadImagesController::class, 'catogeriesupload'])->name('upload-catagoties');

    Route::post('/categories/store', [UploadImagesController::class, 'store'])->name('categories.store');
    Route::get('/categories/show', [UploadImagesController::class, 'show'])->name('categories.show');


    Route::get('/add-image', [UploadImagesController::class, 'imageupload'])->name('add-image');

    Route::get('/get-images', [UploadImagesController::class, 'getimage'])->name('get-images');



    Route::post('/image/store', [UploadImagesController::class, 'imagestore'])->name('image.store');
    Route::delete('/delete-image/{id}', [UploadImagesController::class, 'deleteImage']);


    Route::get('/display-image/{filename}', [UploadImagesController::class, 'displayImage']);

    Route::resource('coupons', CouponController::class);

    Route::post('/apply-coupon', [PaymentController::class, 'applyCoupon'])->name('apply.coupon');
    Route::post('/process-payment', [PaymentController::class, 'processPayment']);
    Route::get('/success', [PaymentController::class, 'successPage'])->name('payment.success');

});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

