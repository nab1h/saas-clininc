<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Dashboard\AppointmentController;
use App\Http\Controllers\Dashboard\ArticleController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\LinkController;
use App\Http\Controllers\Dashboard\PatientController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/book', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
Route::get('/book/success', [BookingController::class, 'success'])->name('booking.success');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/booking', [\App\Http\Controllers\Dashboard\BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{appointment}', [\App\Http\Controllers\Dashboard\BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{appointment}/status', [\App\Http\Controllers\Dashboard\BookingController::class, 'updateStatus'])->name('booking.updateStatus');
});
