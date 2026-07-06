<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public routes - only login
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

// Protected routes - require pegawai authentication
Route::middleware('pegawai.auth')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Shared master data for Admin and Owner
    Route::resource('customer', App\Http\Controllers\CustomerController::class)->except(['show']);
    Route::resource('products', App\Http\Controllers\ProductController::class)->except(['show']);
    Route::post('customer/quick-store', [App\Http\Controllers\CustomerController::class, 'storeQuick'])->name('customer.storeQuick');

    // Owner-only master data
    Route::middleware('owner')->group(function () {
        Route::resource('pegawai', App\Http\Controllers\PegawaiController::class)->except(['show']);
    });

    // Purchase order: everyone can view, only admin can modify
    Route::prefix('purchase_order')->name('purchase_order.')->group(function () {
        Route::get('/', [App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/detail', [App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/print', [App\Http\Controllers\PurchaseOrderController::class, 'print'])->name('print');
    });

    Route::middleware('admin')->prefix('purchase_order')->name('purchase_order.')->group(function () {
        Route::get('/create', [App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/store-initial', [App\Http\Controllers\PurchaseOrderController::class, 'storeInitial'])->name('storeInitial');
        Route::get('/edit', [App\Http\Controllers\PurchaseOrderController::class, 'edit'])->name('edit');
        Route::get('/item/edit', [App\Http\Controllers\PurchaseOrderController::class, 'editItem'])->name('editItem');
        Route::post('/add-item', [App\Http\Controllers\PurchaseOrderController::class, 'addItem'])->name('addItem');
        Route::post('/item/update', [App\Http\Controllers\PurchaseOrderController::class, 'updateItem'])->name('updateItem');
        Route::delete('/item/delete', [App\Http\Controllers\PurchaseOrderController::class, 'destroyItem'])->name('destroyItem');
        Route::delete('/delete', [App\Http\Controllers\PurchaseOrderController::class, 'destroy'])->name('destroy');
    });

    // Invoice: everyone can view, only admin can modify
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [App\Http\Controllers\InvoiceController::class, 'index'])->name('index');
        Route::get('/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('print');
    });

    Route::middleware('admin')->prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/create', [App\Http\Controllers\InvoiceController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\InvoiceController::class, 'store'])->name('store');
        Route::delete('/delete', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/po-view', [App\Http\Controllers\InvoiceController::class, 'showPO'])->name('showPO');
    });
});

