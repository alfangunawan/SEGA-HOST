<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConfigurationProfileController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\Product;
use App\Http\Controllers\User\RentalController as UserRentalController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('configurations', ConfigurationProfileController::class)->except(['show']);
    Route::resource('units', UnitController::class)->except(['show']);
    Route::resource('rentals', AdminRentalController::class)->except(['show']);
    Route::patch('rentals/{rental}/approve', [AdminRentalController::class, 'approve'])->name('rentals.approve');
    Route::patch('rentals/{rental}/reject', [AdminRentalController::class, 'reject'])->name('rentals.reject');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
});

// User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [Product::class, 'index'])->name('products.index');
    Route::get('/products/{unit}', [Product::class, 'show'])->name('products.show');

    // Rental routes
    Route::get('/rentals', [UserRentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create', [UserRentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [UserRentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [UserRentalController::class, 'show'])->name('rentals.show');
    Route::patch('/rentals/{rental}/cancel', [UserRentalController::class, 'cancel'])->name('rentals.cancel');
    Route::post('/rentals/{rental}/extend', [UserRentalController::class, 'extend'])->name('rentals.extend');
    Route::post('/rentals/{rental}/early-return', [UserRentalController::class, 'earlyReturn'])->name('rentals.early-return');
    Route::post('/rentals/{rental}/return', [UserRentalController::class, 'return'])->name('rentals.return');
});

require __DIR__ . '/auth.php';
