<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\MenuController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\Buyer\ReviewController as BuyerReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicVendorController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing - redirect to login or dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect(match (auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'vendor' => route('vendor.dashboard'),
            'buyer' => route('buyer.dashboard'),
        });
    }
    return redirect('/login');
});

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout (requires auth)
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Profile (requires auth, all roles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Public Routes
Route::get('/map', [MapController::class, 'index'])->name('map');
Route::get('/api/vendors-map', [MapController::class, 'vendorsJson'])->name('api.vendors-map');

// Public vendor detail (accessible to all)
Route::get('/vendors/{vendor}', [PublicVendorController::class, 'show'])->name('vendors.show');

// Search (page + AJAX endpoint)
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/results', [SearchController::class, 'search'])->name('search.results');

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Categories CRUD
    Route::resource('categories', CategoryController::class);

    // Vendor Management
    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{vendor}', [AdminVendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])->name('vendors.approve');
    Route::post('/vendors/{vendor}/reject', [AdminVendorController::class, 'reject'])->name('vendors.reject');

    // Review moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // User management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Email logs
    Route::get('/email-logs', [EmailLogController::class, 'index'])->name('email-logs.index');
});

// Vendor Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

    // Vendor CRUD (my vendors)
    Route::resource('vendors', VendorController::class);
    Route::patch('/vendors/{vendor}/toggle-open', [VendorController::class, 'toggleOpen'])->name('vendors.toggle-open');

    // Menu CRUD (my menus)
    Route::resource('menus', MenuController::class);
    Route::patch('/menus/{menu}/toggle-available', [MenuController::class, 'toggleAvailable'])->name('menus.toggle-available');
    Route::post('/menus/{menu}/apply-filter', [MenuController::class, 'applyFilter'])->name('menus.apply-filter');
});

// Buyer Routes
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');

    // Review CRUD
    Route::get('/reviews', [BuyerReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [BuyerReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [BuyerReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [BuyerReviewController::class, 'destroy'])->name('reviews.destroy');
});
