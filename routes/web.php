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
Route::post('/save-push-notification-token', [App\Http\Controllers\HomeController::class, 'savePushNotificationToken'])->name('save-push-notification-token');
Route::post('/send-push-notification', [App\Http\Controllers\HomeController::class, 'sendPushNotification'])->name('send.push-notification');
