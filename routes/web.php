<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckGroup;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->withoutMiddleware(CheckGroup::class);

Route::get('/menus/{menu}', [DashboardController::class, 'menus'])->name('menus');

Route::get('/dashboard/{view?}', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'can:view superstore'])->name('dashboard');
Route::get('/world-indicator', [DashboardController::class, 'world'])->middleware(['auth', 'verified', 'can:view world indicator'])->name('world');
Route::get('/stats', [DashboardController::class, 'stats'])->middleware(['auth', 'verified', 'can:view statistics'])->name('stats');
Route::get('/menus', [DashboardController::class, 'menus'])->middleware(['auth', 'verified', 'can:view menus'])->name('menus');
Route::get('/menus/{slug}', [DashboardController::class, 'menus'])->middleware(['auth', 'verified'])->name('menus.view');
Route::post('/menu', [MenuController::class, 'store'])->middleware(['auth', 'verified'])->name('menu.store');
Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->middleware(['auth', 'verified'])->name('menu.destroy');
Route::patch('/menus/{menu}', [MenuController::class, 'update'])->middleware(['auth', 'verified'])->name('menu.update');

Route::controller(UsersController::class)->group(function () {
    Route::get('/users', 'show')->name('users');
    Route::put('/users/{id}', 'save')->name('users.save');
    Route::delete('/users/{id}', 'destroy')->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
