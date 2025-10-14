<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\POController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TwodaysController;
use App\Http\Controllers\DIController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);
Route::resource('vendors', VendorController::class);
Route::resource('parts', PartController::class);
Route::resource('po', POController::class);
Route::resource('stocks', StockController::class);
Route::resource('twohk', TwodaysController::class);
Route::resource('di', DIController::class);