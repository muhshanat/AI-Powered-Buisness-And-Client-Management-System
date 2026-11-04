<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TwoFactorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\LoginSessionController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Customer Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Customer Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Login and Logout
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AdminLoginController::class, 'login'])
        ->name('login.submit');

    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->name('logout');

        // Activity Logs - Super Admin Only
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('activity-logs.index');

    // Notification Management
    Route::prefix('notifications')->name('notifications.')
    ->middleware(['auth', '2fa'])
    ->group(function () {

        Route::get('/', [NotificationController::class, 'index'])
            ->name('index');

        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])
            ->name('read');

        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name('readAll');

        Route::delete('/{id}', [NotificationController::class, 'destroy'])
            ->name('destroy');
    });

    
   // Login Sessions
   Route::get('/sessions', [LoginSessionController::class, 'index'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('sessions.index');

   // Logout all other sessions - MUST come before {id}
   Route::delete('/sessions/destroy-others', [LoginSessionController::class, 'destroyOthers'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('sessions.destroyOthers');

   // Terminate individual session
   Route::delete('/sessions/{id}', [LoginSessionController::class, 'destroy'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('sessions.destroy');

    // Google Authenticator - 2FA Routes
    Route::middleware('auth')->group(function () {

        Route::get('/2fa/setup', [TwoFactorController::class, 'setup'])
            ->name('2fa.setup');

        Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])
            ->name('2fa.enable');

        Route::get('/2fa/verify', [TwoFactorController::class, 'showVerify'])
            ->name('2fa.verify');

        Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])
            ->name('2fa.verify.submit');

        Route::get('/2fa/settings', [TwoFactorController::class, 'settings'])
    ->middleware(['auth', 'superadmin'])
    ->name('2fa.settings');

        Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])
       ->middleware(['2fa', 'superadmin'])
       ->name('2fa.disable');

    });


    // Super Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['auth', '2fa'])->name('dashboard');


    // Customer Management
    Route::prefix('customers')
        ->name('customers.')
        ->middleware(['auth', '2fa'])
        ->group(function () {

            Route::get('/', [CustomerController::class, 'index'])
                ->middleware('permission:customers.view')
                ->name('index');

            Route::get('/create', [CustomerController::class, 'create'])
                ->middleware('permission:customers.create')
                ->name('create');

            Route::post('/', [CustomerController::class, 'store'])
                ->middleware('permission:customers.create')
                ->name('store');

            Route::get('/trash', [CustomerController::class, 'trash'])
                ->middleware('permission:customers.delete')
                ->name('trash');

            Route::post('/{id}/restore', [CustomerController::class, 'restore'])
                ->middleware('permission:customers.delete')
                ->name('restore');

            Route::get('/{customer}/edit', [CustomerController::class, 'edit'])
                ->middleware('permission:customers.edit')
                ->name('edit');

            Route::put('/{customer}', [CustomerController::class, 'update'])
                ->middleware('permission:customers.edit')
                ->name('update');

            Route::delete('/{customer}', [CustomerController::class, 'destroy'])
                ->middleware('permission:customers.delete')
                ->name('destroy');
        });


    // Role Management
    Route::prefix('roles')
        ->name('roles.')
        ->middleware(['auth', '2fa'])
        ->group(function () {

            Route::get('/', [RoleController::class, 'index'])
                ->middleware('permission:roles.view')
                ->name('index');

            Route::get('/create', [RoleController::class, 'create'])
                ->middleware('permission:roles.create')
                ->name('create');

            Route::post('/', [RoleController::class, 'store'])
                ->middleware('permission:roles.create')
                ->name('store');

            Route::get('/trash', [RoleController::class, 'trash'])
                ->middleware('permission:roles.delete')
                ->name('trash');

            Route::post('/{id}/restore', [RoleController::class, 'restore'])
                ->middleware('permission:roles.delete')
                ->name('restore');

            Route::get('/{role}/edit', [RoleController::class, 'edit'])
                ->middleware('permission:roles.edit')
                ->name('edit');

            Route::put('/{role}', [RoleController::class, 'update'])
                ->middleware('permission:roles.edit')
                ->name('update');

            Route::delete('/{role}', [RoleController::class, 'destroy'])
                ->middleware('permission:roles.delete')
                ->name('destroy');
        });


    // Admin Management
    Route::prefix('admins')
        ->name('admins.')
        ->middleware(['auth', '2fa'])
        ->group(function () {

            Route::get('/', [AdminController::class, 'index'])
                ->middleware('permission:admins.view')
                ->name('index');

            Route::get('/create', [AdminController::class, 'create'])
                ->middleware('permission:admins.create')
                ->name('create');

            Route::post('/', [AdminController::class, 'store'])
                ->middleware('permission:admins.create')
                ->name('store');

            Route::get('/trash', [AdminController::class, 'trash'])
                ->middleware('permission:admins.delete')
                ->name('trash');

            Route::post('/{id}/restore', [AdminController::class, 'restore'])
                ->middleware('permission:admins.delete')
                ->name('restore');

            Route::get('/{admin}/edit', [AdminController::class, 'edit'])
                ->middleware('permission:admins.edit')
                ->name('edit');

            Route::put('/{admin}', [AdminController::class, 'update'])
                ->middleware('permission:admins.edit')
                ->name('update');

            Route::delete('/{admin}', [AdminController::class, 'destroy'])
                ->middleware('permission:admins.delete')
                ->name('destroy');
        });

});

require __DIR__.'/auth.php';
