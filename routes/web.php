
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\CustomerController;
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

// Super Admin routes
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AdminLoginController::class, 'login'])
        ->name('login.submit');

    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['auth', 'superadmin'])->name('dashboard');

    
    // Customer Management
    Route::middleware(['auth', 'superadmin'])
        ->prefix('customers')
        ->name('customers.')
        ->group(function () {
            Route::get('/', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'index'
            ])->name('index');

            Route::get('/create', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'create'
            ])->name('create');

            Route::post('/', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'store'
            ])->name('store');

            Route::get('/{customer}/edit', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'edit'
            ])->name('edit');

            Route::put('/{customer}', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'update'
            ])->name('update');

            Route::delete('/{customer}', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'destroy'
            ])->name('destroy');

            
            Route::get('/trash', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'trash'
            ])->name('trash');

            Route::post('/{id}/restore', [
                \App\Http\Controllers\Admin\CustomerController::class,
                'restore'
            ])->name('restore');

            

        });

        
Route::middleware(['auth', 'superadmin'])
    ->prefix('roles')
    ->name('roles.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RoleController::class, 'index'])
            ->name('index');

        Route::get('/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])
            ->name('create');

        Route::post('/', [\App\Http\Controllers\Admin\RoleController::class, 'store'])
            ->name('store');

        Route::get('/trash', [\App\Http\Controllers\Admin\RoleController::class, 'trash'])
            ->name('trash');

        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\RoleController::class, 'restore'])
            ->name('restore');

        Route::get('/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])
            ->name('edit');

        Route::put('/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])
            ->name('update');

        Route::delete('/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])
            ->name('destroy');
    });

    
Route::middleware(['auth', 'superadmin'])
    ->prefix('admins')
    ->name('admins.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdminController::class, 'store'])->name('store');
        Route::get('/trash', [\App\Http\Controllers\Admin\AdminController::class, 'trash'])->name('trash');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\AdminController::class, 'restore'])->name('restore');
        Route::get('/{admin}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [\App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('destroy');
    });



});

require __DIR__.'/auth.php';
