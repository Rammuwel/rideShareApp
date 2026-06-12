<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//auth routes
Route::post('/login', [LoginAuthController::class, 'store'])->name('post.login');
Route::post('/login/verify', [LoginAuthController::class, 'verify'])->name('post.login.verify');


Route::group(['middleware' => "auth:sanctum"], function () {
    //user's routes
    Route::get('/user', [UserController::class, 'show'])->name('get.user.show');


    //driver's routes
    Route::get('/driver', [DriverController::class, 'show'])->name('get.driver.show');
    Route::post('/driver', [DriverController::class, 'store'])->name('post.driver.store');

    //trip  rooutes
    Route::get('/trips', [TripController::class, 'index'])->name('get.trips.all');
    Route::get('/trip/{trip}', [TripController::class, 'show'])->name('get.trip.show');
    Route::post('/trip', [TripController::class, 'create'])->name('post.trip.create');
    Route::post('/trip/{trip}/accept', [TripController::class, 'accept'])->name('post.trip.accept');
    Route::post('/trip/{trip}/start', [TripController::class, 'start'])->name('post.trip.start');
    Route::post('/trip/{trip}/end', [TripController::class, 'end'])->name('post.trip.end');
    Route::post('/trip/{trip}/location', [TripController::class, 'location'])->name('post.trip.location');

});
