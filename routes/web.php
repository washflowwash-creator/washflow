<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:customer')->group(function () {
        Route::resource('bookings', BookingController::class);
        Route::get('/portal', [CustomerPortalController::class, 'index'])->name('customer.portal');
    });

    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
        Route::resource('orders', OrderController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('inventories', InventoryController::class);
        Route::get('/service-rates', [ServiceRateController::class, 'index'])->name('service-rates.index');
        Route::post('/service-rates/bulk-update', [ServiceRateController::class, 'bulkUpdate'])->name('service-rates.bulk-update');
        Route::patch('/service-rates/{serviceRate}', [ServiceRateController::class, 'update'])->name('service-rates.update');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
    });
});

require __DIR__.'/auth.php';
