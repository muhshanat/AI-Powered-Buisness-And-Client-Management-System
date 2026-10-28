
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Admin login and logout
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AdminLoginController::class, 'login'])
        ->name('login.submit');

    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->name('logout');

    // Super Admin dashboard only
    Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('dashboard');

    // Customer Management
    Route::prefix('customers')->name('customers.')->middleware('auth')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])
            ->middleware('permission:customers.view')->name('index');

        Route::get('/create', [CustomerController::class, 'create'])
            ->middleware('permission:customers.create')->name('create');

        Route::post('/', [CustomerController::class, 'store'])
            ->middleware('permission:customers.create')->name('store');

        Route::get('/trash', [CustomerController::class, 'trash'])
            ->middleware('permission:customers.delete')->name('trash');

        Route::post('/{id}/restore', [CustomerController::class, 'restore'])
            ->middleware('permission:customers.delete')->name('restore');

        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])
            ->middleware('permission:customers.edit')->name('edit');

        Route::put('/{customer}', [CustomerController::class, 'update'])
            ->middleware('permission:customers.edit')->name('update');

        Route::delete('/{customer}', [CustomerController::class, 'destroy'])
            ->middleware('permission:customers.delete')->name('destroy');
    });

    // Role Management
    Route::prefix('roles')->name('roles.')->middleware('auth')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware('permission:roles.view')->name('index');

        Route::get('/create', [RoleController::class, 'create'])
            ->middleware('permission:roles.create')->name('create');

        Route::post('/', [RoleController::class, 'store'])
            ->middleware('permission:roles.create')->name('store');

        Route::get('/trash', [RoleController::class, 'trash'])
            ->middleware('permission:roles.delete')->name('trash');

        Route::post('/{id}/restore', [RoleController::class, 'restore'])
            ->middleware('permission:roles.delete')->name('restore');

        Route::get('/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:roles.edit')->name('edit');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->middleware('permission:roles.edit')->name('update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:roles.delete')->name('destroy');
    });

    // Admin Management
    Route::prefix('admins')->name('admins.')->middleware('auth')->group(function () {
        Route::get('/', [AdminController::class, 'index'])
            ->middleware('permission:admins.view')->name('index');

        Route::get('/create', [AdminController::class, 'create'])
            ->middleware('permission:admins.create')->name('create');

        Route::post('/', [AdminController::class, 'store'])
            ->middleware('permission:admins.create')->name('store');

        Route::get('/trash', [AdminController::class, 'trash'])
            ->middleware('permission:admins.delete')->name('trash');

        Route::post('/{id}/restore', [AdminController::class, 'restore'])
            ->middleware('permission:admins.delete')->name('restore');

        Route::get('/{admin}/edit', [AdminController::class, 'edit'])
            ->middleware('permission:admins.edit')->name('edit');

        Route::put('/{admin}', [AdminController::class, 'update'])
            ->middleware('permission:admins.edit')->name('update');

        Route::delete('/{admin}', [AdminController::class, 'destroy'])
            ->middleware('permission:admins.delete')->name('destroy');
    });
});

require __DIR__.'/auth.php';
