<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TwoFactorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\LoginSessionController;
use App\Http\Controllers\AccountSwitcherController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\Admin\AppearanceController;


// Home
Route::get('/', function () {
    return view('welcome');
});

//  Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Organization Dashboard
Route::middleware(['auth', 'organization'])
    ->prefix('organization')
    ->name('organization.')
    ->group(function () {
        Route::get('/dashboard', [
            \App\Http\Controllers\Organization\DashboardController::class,
            'index'
        ])->name('dashboard');
    });

// organization Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

       // Profile Picture Routes
Route::post('/profile/photo', [ProfilePhotoController::class, 'update'])
    ->name('profile.photo.update');

Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])
    ->name('profile.photo.destroy');
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

        Route::post('/appearance/reset', [AppearanceController::class, 'reset'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('appearance.reset');


    // Appearance Settings - Super Admin Only
Route::get('/appearance', [AppearanceController::class, 'index'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('appearance.index');

Route::post('/appearance', [AppearanceController::class, 'update'])
    ->middleware(['auth', '2fa', 'superadmin'])
    ->name('appearance.update');



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


    //switch to organization account

    Route::post('/switch-to-organization', function (Illuminate\Http\Request $request) {
    auth()->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')
        ->with('status', 'Please log in with your organization account.');
})->middleware(['auth', 'superadmin'])->name('switch-to-organization');


    // Super Admin Dashboard
   // Super Admin Dashboard
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', '2fa', 'superadmin'])->name('dashboard');



// Trash Management Main Page
Route::get('/trash', function (\Illuminate\Http\Request $request) {

    $user = auth()->user();

    $isSuperAdmin = $user->role === 'super_admin';

    $role = \App\Models\Role::where('name', $user->role)->first();
    $permissions = $role->permissions ?? [];

    $canViewOrganizationTrash = $isSuperAdmin || in_array('organizations.delete', $permissions);
    $canViewRoleTrash = $isSuperAdmin || in_array('roles.delete', $permissions);
    $canViewAdminTrash = $isSuperAdmin || in_array('admins.delete', $permissions);

    abort_unless(
        $canViewOrganizationTrash || $canViewRoleTrash || $canViewAdminTrash,
        403
    );

    $organizations = $canViewOrganizationTrash
    ? \App\Models\Organization::onlyTrashed()
        ->latest('deleted_at')
        ->get()
    : collect();

    $roles = $canViewRoleTrash
        ? \App\Models\Role::onlyTrashed()
            ->latest('deleted_at')
            ->get()
        : collect();

    $admins = $canViewAdminTrash
        ? \App\Models\Admin::onlyTrashed()
            ->latest('deleted_at')
            ->get()
        : collect();

    return view('admin.trash.index', compact(
        'organizations',
        'roles',
        'admins',
        'canViewOrganizationTrash',
        'canViewRoleTrash',
        'canViewAdminTrash'
    ));

})->middleware(['auth', '2fa'])->name('trash.index');



    // organization Management
    Route::prefix('organizations')
        ->name('organizations.')
        ->middleware(['auth', '2fa'])
        ->group(function () {

            Route::get('/', [OrganizationController::class, 'index'])
                ->middleware('permission:organizations.view')
                ->name('index');

            Route::get('/create', [OrganizationController::class, 'create'])
                ->middleware('permission:organizations.create')
                ->name('create');

            Route::post('/', [OrganizationController::class, 'store'])
                ->middleware('permission:organizations.create')
                ->name('store');

            Route::get('/trash', [OrganizationController::class, 'trash'])
                ->middleware('permission:organizations.delete')
                ->name('trash');

            Route::post('/{id}/restore', [OrganizationController::class, 'restore'])
                ->middleware('permission:organizations.delete')
                ->name('restore');

            Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])
                ->middleware('permission:organizations.edit')
                ->name('edit');

            Route::put('/{organization}', [OrganizationController::class, 'update'])
                ->middleware('permission:organizations.edit')
                ->name('update');

            Route::delete('/{organization}', [OrganizationController::class, 'destroy'])
                ->middleware('permission:organizations.delete')
                ->name('destroy');

                Route::get('/{organization}', [OrganizationController::class, 'show'])
    ->middleware('permission:organizations.view')
    ->name('show');
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


// Multiple Account Switcher
Route::middleware('auth')->group(function () {

    Route::post('/accounts/add', [AccountSwitcherController::class, 'addAccount'])
        ->name('accounts.add');

    Route::post('/accounts/switch/{id}', [AccountSwitcherController::class, 'switchAccount'])
        ->name('accounts.switch');

    Route::delete('/accounts/remove/{id}', [AccountSwitcherController::class, 'removeAccount'])
        ->name('accounts.remove');

});

require __DIR__.'/auth.php';
