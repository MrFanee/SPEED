<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\twodays\TwodaysIndexController;
use App\Http\Controllers\twodays\TwodaysCreateController;
use App\Http\Controllers\twodays\TwodaysStoreController;
use App\Http\Controllers\twodays\TwodaysEditController;
use App\Http\Controllers\twodays\TwodaysUpdateController;
use App\Http\Controllers\twodays\TwodaysDestroyController;

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

    // Twodays routes
    Route::get('/twodays/view', [TwodaysIndexController::class, 'index'])->name('twodays.index');
    Route::get('/twodays/create', [TwodaysCreateController::class, 'create'])->name('twodays.create');
    Route::post('/twodays/store', [TwodaysStoreController::class, 'store'])->name('twodays.store');
    Route::get('/twodays/edit/{id}', [TwodaysEditController::class, 'edit'])->name('twodays.edit');
    Route::put('/twodays/update/{id}', [TwodaysUpdateController::class, 'update'])->name('twodays.update');
    Route::delete('/twodays/delete/{id}', [TwodaysDestroyController::class, 'destroy'])->name('twodays.delete');

    // Resource routes
    Route::resource('users', 'UserController');
    Route::resource('po', 'POControllers');
    Route::resource('stocks', 'StockController');
    Route::resource('di', 'DIController');
});
