<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;




Route::get('/login', function () {
     return view('login');
})->name('login');

Route::group(['middleware' => "auth:sanctum"], function () {
     //user's routes
     Route::get('/', [WebController::class, 'index'])->name('home');
     Route::get('/driver/login', [WebController::class, 'login_driver'])->name('driver.login');
     Route::get('/driver/home', [WebController::class, 'driver'])->name('driver.home');
     Route::get('/trip/deriver/{trip}', [WebController::class, 'tripDriver'])->name('get.tripDriver');  
     Route::get('/trip/{trip}', [WebController::class, 'tripUser'])->name('get.tripUser');  
});
