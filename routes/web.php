<?php

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
    return view('otp');
});
// Route::get('phone-auth','PhoneAuthController@index');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('push-notification', [App\Http\Controllers\NotificationController::class, 'index']);
Route::post('sendNotification', [App\Http\Controllers\NotificationController::class, 'sendNotification'])->name('send.notification');

Route::get('2fa', [App\Http\Controllers\TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('2fa', [App\Http\Controllers\TwoFactorController::class, 'store'])->name('2fa.post');
Route::get('2fa/reset', [App\Http\Controllers\TwoFactorController::class, 'resend'])->name('2fa.resend');

Route::get('paywithpaypal',[App\Http\Controllers\PaypalController::class,'payWithPaypal'])->name('paywithpaypal');
Route::post('postpaywithpaypal',[App\Http\Controllers\PaypalController::class,'postPayWithPaypal'])->name('postpaypal');
// Route::get('status',[App\Http\Controllers\PaypalController::class,'paymentStatus'])->name('status');

Route::get('success', [App\Http\Controllers\PaypalController::class, 'success']);
Route::get('error', [App\Http\Controllers\PaypalController::class, 'error']);
