<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Dashboard\AppointmentController;
use App\Http\Controllers\Dashboard\ArticleController;
use App\Http\Controllers\Dashboard\ClinicController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\LinkController;
use App\Http\Controllers\Dashboard\PatientController;
use App\Http\Controllers\Dashboard\ServiceController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test route
Route::get('/test-register', function () {
    return view('auth.test-register');
})->name('test.register');

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:6,1');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])
    ->middleware('throttle:6,1');

Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Social Authentication Routes
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback');

Route::get('/book', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
Route::get('/book/success', [BookingController::class, 'success'])->name('booking.success');

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', \App\Http\Middleware\CheckUserClinic::class])->group(function () {
    Route::post('/switch-clinic', function (Request $request) {
        $clinicId = $request->input('clinic_id');
        $user = Auth::user();

        if ($user && $user->clinics->where('id', $clinicId)->exists()) {
            session()->put('current_clinic_id', $clinicId);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'غير مصرح لك بالوصول لهذه العيادة']);
    })->name('switch.clinic');

    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/assign-clinic', [UserController::class, 'assignClinic'])->name('users.assignClinic');
    Route::post('/users/{user}/remove-clinic', [UserController::class, 'removeClinic'])->name('users.removeClinic');
    Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics.index');
    Route::get('/clinics/create', [ClinicController::class, 'create'])->name('clinics.create');
    Route::post('/clinics', [ClinicController::class, 'store'])->name('clinics.store');
    Route::get('/clinics/{clinic}/edit', [ClinicController::class, 'edit'])->name('clinics.edit');
    Route::put('/clinics/{clinic}', [ClinicController::class, 'update'])->name('clinics.update');
    Route::delete('/clinics/{clinic}', [ClinicController::class, 'destroy'])->name('clinics.destroy');
    Route::post('/clinics/{clinic}/toggle', [ClinicController::class, 'toggleStatus'])->name('clinics.toggle');
    Route::post('/clinics/{clinic}/assign', [ClinicController::class, 'assignManager'])->name('clinics.assign');
    Route::post('/clinics/{clinic}/remove-user', [ClinicController::class, 'removeUser'])->name('clinics.removeUser');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{article}/favorite', [ArticleController::class, 'toggleFavorite'])->name('articles.favorite');
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::get('/links/create', [LinkController::class, 'create'])->name('links.create');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::get('/links/{link}/edit', [LinkController::class, 'edit'])->name('links.edit');
    Route::put('/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/booking', [\App\Http\Controllers\Dashboard\BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{appointment}', [\App\Http\Controllers\Dashboard\BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{appointment}/status', [\App\Http\Controllers\Dashboard\BookingController::class, 'updateStatus'])->name('booking.updateStatus');
});
