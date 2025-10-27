<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\Product;
use App\Http\Controllers\User\RentalController;

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
    Route::resource('units', UnitController::class)->except(['show']);
    Route::resource('rentals', RentalController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
});

// User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [Product::class, 'index'])->name('products.index');
    Route::get('/products/{unit}', [Product::class, 'show'])->name('products.show');
    
    // Rental routes
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::patch('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    Route::post('/rentals/{rental}/extend', [RentalController::class, 'extend'])->name('rentals.extend');
    Route::post('/rentals/{rental}/early-return', [RentalController::class, 'earlyReturn'])->name('rentals.early-return'); // New route
    Route::post('/rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
});

require __DIR__ . '/auth.php';
