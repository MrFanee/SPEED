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
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin', function () {
        return 'Halo Admin!';
    })->middleware('role:admin');

    Route::get('/staff', function () {
        return 'Halo Staff!';
    })->middleware('role:staff,admin');

    Route::get('/vendor', function () {
        return 'Halo Vendor!';
    })->middleware('role:vendor,admin');

    // Resource routes
    Route::resource('users', 'UserController');
    Route::resource('vendors', 'VendorController');
    Route::resource('parts', 'PartController');
    Route::resource('po', 'POControllers');
    Route::resource('stocks', 'StockController');
    Route::resource('twohk', 'TwodaysController');
    Route::resource('di', 'DIController');
});
