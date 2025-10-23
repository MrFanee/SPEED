<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\POController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TwodaysController;
use App\Http\Controllers\DIController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\vendor\VendorIndexController;
use App\Http\Controllers\vendor\VendorCreateController;
use App\Http\Controllers\vendor\VendorStoreController;
use App\Http\Controllers\vendor\VendorEditController;
use App\Http\Controllers\vendor\VendorUpdateController;
use App\Http\Controllers\vendor\VendorDestroyController;
use App\Http\Controllers\part\PartIndexController;
use App\Http\Controllers\part\PartCreateController;
use App\Http\Controllers\part\PartStoreController;
use App\Http\Controllers\part\PartEditController;
use App\Http\Controllers\part\PartUpdateController;
use App\Http\Controllers\part\PartDestroyController;

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

    // Vendor routes
    Route::get('/vendor/view', [VendorIndexController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/create', [VendorCreateController::class, 'create'])->name('vendor.create');
    Route::post('/vendor/store', [VendorStoreController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/edit/{id}', [VendorEditController::class, 'edit'])->name('vendor.edit');
    Route::put('/vendor/update/{id}', [VendorUpdateController::class, 'update'])->name('vendor.update');
    Route::delete('/vendor/delete/{id}', [VendorDestroyController::class, 'destroy'])->name('vendor.delete');

    // Part routes
    Route::get('/part/view', [PartIndexController::class, 'index'])->name('part.index');
    Route::get('/part/create', [PartCreateController::class, 'create'])->name('part.create');
    Route::post('/part/store', [PartStoreController::class, 'store'])->name('part.store');
    Route::get('/part/edit/{id}', [PartEditController::class, 'edit'])->name('part.edit');
    Route::put('/part/update/{id}', [PartUpdateController::class, 'update'])->name('part.update');
    Route::delete('/part/delete/{id}', [PartDestroyController::class, 'destroy'])->name('part.delete');

    // Resource routes
    Route::resource('users', 'UserController');
    Route::resource('po', 'POControllers');
    Route::resource('stocks', 'StockController');
    Route::resource('twohk', 'TwodaysController');
    Route::resource('di', 'DIController');
});
