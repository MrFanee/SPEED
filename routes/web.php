<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\vendor\VendorIndexController;
use App\Http\Controllers\vendor\VendorCreateController;
use App\Http\Controllers\vendor\VendorStoreController;
use App\Http\Controllers\vendor\VendorEditController;
use App\Http\Controllers\vendor\VendorUpdateController;
use App\Http\Controllers\vendor\VendorDestroyController;
use App\Http\Controllers\part\PartIndexController;
use App\Http\Controllers\part\PartCreateController;
use App\Http\Controllers\part\PartUploadController;
use App\Http\Controllers\part\PartStoreController;
use App\Http\Controllers\part\PartEditController;
use App\Http\Controllers\part\PartUpdateController;
use App\Http\Controllers\part\PartDestroyController;
use App\Http\Controllers\twodays\TwodaysIndexController;
use App\Http\Controllers\twodays\TwodaysCreateController;
use App\Http\Controllers\twodays\TwodaysUploadController;
use App\Http\Controllers\twodays\TwodaysStoreController;
use App\Http\Controllers\twodays\TwodaysEditController;
use App\Http\Controllers\twodays\TwodaysUpdateController;
use App\Http\Controllers\twodays\TwodaysDestroyController;
use App\Http\Controllers\di\DIIndexController;
use App\Http\Controllers\di\DICreateController;
use App\Http\Controllers\di\DIUploadController;
use App\Http\Controllers\di\DIStoreController;
use App\Http\Controllers\di\DIEditController;
use App\Http\Controllers\di\DIUpdateController;
use App\Http\Controllers\di\DIDestroyController;
use App\Http\Controllers\po\POIndexController;
use App\Http\Controllers\po\POCreateController;
use App\Http\Controllers\po\POUploadController;
use App\Http\Controllers\po\POStoreController;
use App\Http\Controllers\po\POEditController;
use App\Http\Controllers\po\POUpdateController;
use App\Http\Controllers\po\PODestroyController;
use App\Http\Controllers\stock\StockIndexController;
use App\Http\Controllers\stock\StockUploadController;
use App\Http\Controllers\stock\StockUpdateController;
use App\Http\Controllers\stock\StockCreateController;
use App\Http\Controllers\report\ReportVendorController;
use App\Http\Controllers\report\ReportMonthlyController;
use App\Http\Controllers\report\ReportYearlyController;
use App\Http\Controllers\user\UserIndexController;
use App\Http\Controllers\user\UserCreateController;
use App\Http\Controllers\user\UserStoreController;
use App\Http\Controllers\user\UserEditController;
use App\Http\Controllers\user\UserUpdateController;
use App\Http\Controllers\user\UserDestroyController;
use App\Http\Controllers\upload_failure\UploadFailureIndexController;
use App\Http\Controllers\upload_failure\UploadFailureDestroyController;
use App\Http\Controllers\monitoring\StockDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/monitoring/stock', [StockDashboardController::class, 'index'])->name('monitoring.stock');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::get('/part/upload', [PartUploadController::class, 'form'])->name('part.upload.form');
    Route::post('/part/upload', [PartUploadController::class, 'upload'])->name('part.upload');
    Route::post('/part/store', [PartStoreController::class, 'store'])->name('part.store');
    Route::get('/part/edit/{id}', [PartEditController::class, 'edit'])->name('part.edit');
    Route::put('/part/update/{id}', [PartUpdateController::class, 'update'])->name('part.update');
    Route::delete('/part/delete/{id}', [PartDestroyController::class, 'destroy'])->name('part.delete');

    // Twodays routes
    Route::get('/twodays/view', [TwodaysIndexController::class, 'index'])->name('twodays.index');
    Route::get('/twodays/create', [TwodaysCreateController::class, 'create'])->name('twodays.create');
    Route::get('/twodays/upload', [TwodaysUploadController::class, 'form'])->name('twodays.upload.form');
    Route::post('/twodays/upload', [TwodaysUploadController::class, 'upload'])->name('twodays.upload');
    Route::post('/twodays/store', [TwodaysStoreController::class, 'store'])->name('twodays.store');
    Route::get('/twodays/edit/{id}', [TwodaysEditController::class, 'edit'])->name('twodays.edit');
    Route::put('/twodays/update/{id}', [TwodaysUpdateController::class, 'update'])->name('twodays.update');
    Route::delete('/twodays/delete/{id}', [TwodaysDestroyController::class, 'destroy'])->name('twodays.delete');

    // DI routes
    Route::get('/di/view', [DIIndexController::class, 'index'])->name('di.index');
    Route::get('/di/create', [DICreateController::class, 'create'])->name('di.create');
    Route::get('/di/upload', [DIUploadController::class, 'form'])->name('di.upload.form');
    Route::post('/di/upload', [DIUploadController::class, 'upload'])->name('di.upload');
    Route::post('/di/store', [DIStoreController::class, 'store'])->name('di.store');
    Route::get('/di/edit/{id}', [DIEditController::class, 'edit'])->name('di.edit');
    Route::put('/di/update/{id}', [DIUpdateController::class, 'update'])->name('di.update');
    Route::delete('/di/delete/{id}', [DIDestroyController::class, 'destroy'])->name('di.delete');

    // PO routes
    Route::get('/po/view', [POIndexController::class, 'index'])->name('po.index');
    Route::get('/po/create', [POCreateController::class, 'create'])->name('po.create');
    Route::get('/po/upload', [POUploadController::class, 'form'])->name('po.upload.form');
    Route::post('/po/upload', [POUploadController::class, 'upload'])->name('po.upload');
    Route::post('/po/store', [POStoreController::class, 'store'])->name('po.store');
    Route::get('/po/edit/{id}', [POEditController::class, 'edit'])->name('po.edit');
    Route::put('/po/update/{id}', [POUpdateController::class, 'update'])->name('po.update');
    Route::delete('/po/delete/{id}', [PODestroyController::class, 'destroy'])->name('po.delete');

    // Stock routes
    Route::get('/stock/view', [StockIndexController::class, 'index'])->name('stock.index');
    Route::get('/stock/upload', [StockUploadController::class, 'form'])->name('stock.upload.form');
    Route::post('/stock/upload', [StockUploadController::class, 'upload'])->name('stock.upload');
    Route::post('/stock/update/{id}', [StockUpdateController::class, 'update'])->name('stock.update');
    Route::post('/stock/create', [StockCreateController::class, 'create'])->name('stock.create');

    // Report routes
    Route::get('/report/vendor', [ReportVendorController::class, 'index'])->name('report.vendor');
    Route::get('/report/monthly', [ReportMonthlyController::class, 'index'])->name('report.monthly');
    Route::get('/report/yearly', [ReportYearlyController::class, 'index'])->name('report.yearly');

    // User routes
    Route::get('/user/view', [UserIndexController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserCreateController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserStoreController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserEditController::class, 'edit'])->name('user.edit');
    Route::put('/user/update/{id}', [UserUpdateController::class, 'update'])->name('user.update');
    Route::delete('/user/delete/{id}', [UserDestroyController::class, 'destroy'])->name('user.delete');

    // Upload Failure routes
    Route::get('/upload_failure/index', [UploadFailureIndexController::class, 'index'])->name('upload_failure.index');
    Route::get('/upload_failure/show/{id}', [UploadFailureIndexController::class, 'show'])->name('upload_failure.show');
    Route::post('/upload_failure/retry/{id}', [UploadFailureIndexController::class, 'retry'])->name('upload_failure.retry');
    Route::delete('/upload_failure/delete/{id}', [UploadFailureDestroyController::class, 'destroy'])->name('upload_failure.delete');
});
