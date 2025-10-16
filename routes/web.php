<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/admin', function () {
    return 'Halo Admin!';
})->middleware('role:admin');

Route::get('/staff', function () {
    return 'Halo Staff!';
})->middleware('role:staff,admin');

Route::get('/vendor', function () {
    return 'Halo Vendor!';
})->middleware('role:vendor,admin');


Route::resource('users', UserController::class);
Route::resource('vendors', VendorController::class);
Route::resource('parts', PartController::class);
Route::resource('po', POController::class);
Route::resource('stocks', StockController::class);
Route::resource('twohk', TwodaysController::class);
Route::resource('di', DIController::class);