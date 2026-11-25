
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\LoginSessionController;
use App\Http\Controllers\AccountSwitcherController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Organization\DashboardController;
use App\Http\Controllers\Public\CustomerConversationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


// Home
Route::get('/', function () {
    return view('welcome');
});


// Default Dashboard (Web Guard)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ======================================================
// ORGANIZATION ROUTES
// ======================================================

Route::middleware(['auth:organization', 'organization'])
    ->prefix('organization')
    ->name('organization.')
    ->group(function () {

        // Organization Dashboard
        Route::get('/dashboard', [
            DashboardController::class,
            'index'
        ])->name('dashboard');


        // Product Management
        Route::resource(
            'products',
            \App\Http\Controllers\Organization\ProductController::class
        )->except(['show']);


        // Service Management
        Route::resource(
            'services',
            \App\Http\Controllers\Organization\ServiceController::class
        )->except(['show']);


        // Order Management
        Route::resource(
            'orders',
            \App\Http\Controllers\Organization\OrderController::class
        )->only(['index', 'show', 'update']);


        // Appointment Management
        Route::resource(
            'appointments',
            \App\Http\Controllers\Organization\AppointmentController::class
        )->only(['index', 'show', 'update']);


        // Conversation Management
        Route::resource(
            'conversations',
            \App\Http\Controllers\Organization\ConversationController::class
        )->only(['index', 'show', 'update']);


        // Organization Reply
        Route::post('/conversations/{conversation}/reply', [
            \App\Http\Controllers\Organization\ConversationController::class,
            'store'
        ])->name('conversations.store');


        // Website API Connection
        Route::get('/website-connection', [
            \App\Http\Controllers\Organization\WebsiteConnectionController::class,
            'index'
        ])->name('website-connection.index');


        // Save Website API Credentials
        Route::post('/website-connection', [
            \App\Http\Controllers\Organization\WebsiteConnectionController::class,
            'store'
        ])->name('website-connection.store');


        // Start Product Synchronization
        Route::post('/website-connection/sync', [
            \App\Http\Controllers\Organization\WebsiteConnectionController::class,
            'sync'
        ])->name('website-connection.sync');


        // Synchronize Products in Batches
        Route::post('/website-connection/sync-batch', [
            \App\Http\Controllers\Organization\WebsiteConnectionController::class,
            'syncBatch'
        ])->name('website-connection.sync-batch');


        // Get Synchronized Products
        Route::get('/website-connection/synced-products', [
            \App\Http\Controllers\Organization\WebsiteConnectionController::class,
            'syncedProducts'
        ])->name('website-connection.synced-products');

    });


// ======================================================
// PUBLIC CUSTOMER CHAT ROUTES
// ======================================================

// Customer Contact Form
Route::get('/contact/{organizationId}', [
    CustomerConversationController::class,
    'create'
])->name('public.contact.create');


// Submit Customer Message
Route::post('/contact/{organizationId}', [
    CustomerConversationController::class,
    'store'
])->name('public.contact.store');


// Customer Chat Page
Route::get('/chat/{token}', [
    CustomerConversationController::class,
    'show'
])->name('public.chat.show');


// Customer Reply
Route::post('/chat/{token}/reply', [
    CustomerConversationController::class,
    'reply'
])->name('public.chat.reply');


// ======================================================
// PROFILE ROUTES
// ======================================================

Route::middleware('auth')->group(function () {

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');


    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');


    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');


    // Profile Picture
    Route::post('/profile/photo', [
        ProfilePhotoController::class,
        'update'
    ])->name('profile.photo.update');


    Route::delete('/profile/photo', [
        ProfilePhotoController::class,
        'destroy'
    ])->name('profile.photo.destroy');

});


// ======================================================
// ADMIN ROUTES
// ======================================================

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {


        // Admin Login and Logout
        Route::get('/login', [
            AdminLoginController::class,
            'showLoginForm'
        ])->name('login');


        Route::post('/login', [
            AdminLoginController::class,
            'login'
        ])->name('login.submit');


        Route::post('/logout', [
            AdminLoginController::class,
            'logout'
        ])->name('logout');


        // Appearance Settings - Super Admin Only
        Route::post('/appearance/reset', [
            AppearanceController::class,
            'reset'
        ])->middleware(['auth:superadmin', '2fa', 'superadmin'])
            ->name('appearance.reset');


        Route::get('/appearance', [
            AppearanceController::class,
            'index'
        ])->middleware(['auth:superadmin', '2fa', 'superadmin'])
            ->name('appearance.index');


        Route::post('/appearance', [
            AppearanceController::class,
            'update'
        ])->middleware(['auth:superadmin', '2fa', 'superadmin'])
            ->name('appearance.update');


        // Activity Logs - Super Admin Only
        Route::get('/activity-logs', [
            ActivityLogController::class,
            'index'
        ])->middleware(['auth:superadmin', '2fa', 'superadmin'])
            ->name('activity-logs.index');


        // Notification Management
        Route::prefix('notifications')
            ->name('notifications.')
            ->middleware(['auth:superadmin', '2fa'])
            ->group(function () {

                Route::get('/', [
                    NotificationController::class,
                    'index'
                ])->name('index');


                Route::post('/{id}/read', [
                    NotificationController::class,
                    'markAsRead'
                ])->name('read');


                Route::post('/read-all', [
                    NotificationController::class,
                    'markAllAsRead'
                ])->name('readAll');


                Route::delete('/{id}', [
                    NotificationController::class,
                    'destroy'
                ])->name('destroy');

            });


        // Login Sessions - Super Admin Only
        Route::get('/sessions', [
            LoginSessionController::class,
            'index'
        ])->middleware(['auth:superadmin', '2fa'])
            ->name('sessions.index');


        // Logout All Other Sessions
        Route::delete('/sessions/destroy-others', [
            LoginSessionController::class,
            'destroyOthers'
        ])->middleware(['auth:superadmin', '2fa'])
            ->name('sessions.destroyOthers');


        // Terminate Individual Session
        Route::delete('/sessions/{id}', [
            LoginSessionController::class,
            'destroy'
        ])->middleware(['auth:superadmin', '2fa'])
            ->name('sessions.destroy');


        // ==================================================
        // TWO-FACTOR AUTHENTICATION
        // ==================================================

        Route::middleware('auth:superadmin')->group(function () {

            Route::get('/2fa/setup', [
                TwoFactorController::class,
                'setup'
            ])->name('2fa.setup');


            Route::post('/2fa/enable', [
                TwoFactorController::class,
                'enable'
            ])->name('2fa.enable');


            Route::get('/2fa/verify', [
                TwoFactorController::class,
                'showVerify'
            ])->name('2fa.verify');


            Route::post('/2fa/verify', [
                TwoFactorController::class,
                'verify'
            ])->name('2fa.verify.submit');


            Route::get('/2fa/settings', [
                TwoFactorController::class,
                'settings'
            ])->middleware('superadmin')
                ->name('2fa.settings');


            Route::post('/2fa/disable', [
                TwoFactorController::class,
                'disable'
            ])->middleware(['2fa', 'superadmin'])
                ->name('2fa.disable');

        });


        // ==================================================
        // SWITCH TO ORGANIZATION ACCOUNT
        // ==================================================

        Route::post('/switch-to-organization', function () {

            auth('superadmin')->logout();

            session()->forget([
                'admin_2fa_verified',
                'admin_2fa_pending'
            ]);

            return redirect()->route('login')
                ->with(
                    'status',
                    'Please log in with your organization account.'
                );

        })->middleware(['auth:superadmin', 'superadmin'])
            ->name('switch-to-organization');


        // ==================================================
        // ADMIN DASHBOARD
        // ==================================================

        Route::get('/dashboard', function () {
            return view('admin.dashboard');

        })->middleware(['auth:superadmin', '2fa'])
            ->name('dashboard');


        // ==================================================
        // TRASH MANAGEMENT
        // ==================================================

        Route::get('/trash', function (Request $request) {

            $user = auth('superadmin')->user();

            $isSuperAdmin = $user->role === 'super_admin';

            $role = \App\Models\Role::where(
                'name',
                $user->role
            )->first();

            $permissions = $role->permissions ?? [];

            $canViewOrganizationTrash =
                $isSuperAdmin ||
                in_array('organizations.delete', $permissions);

            $canViewRoleTrash =
                $isSuperAdmin ||
                in_array('roles.delete', $permissions);

            $canViewAdminTrash =
                $isSuperAdmin ||
                in_array('admins.delete', $permissions);


            abort_unless(
                $canViewOrganizationTrash ||
                $canViewRoleTrash ||
                $canViewAdminTrash,
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

        })->middleware(['auth:superadmin', '2fa'])
            ->name('trash.index');


        // ==================================================
        // ORGANIZATION MANAGEMENT
        // ==================================================

        Route::prefix('organizations')
            ->name('organizations.')
            ->middleware(['auth:superadmin', '2fa'])
            ->group(function () {

                Route::get('/', [
                    OrganizationController::class,
                    'index'
                ])->middleware('permission:organizations.view')
                    ->name('index');


                Route::get('/create', [
                    OrganizationController::class,
                    'create'
                ])->middleware('permission:organizations.create')
                    ->name('create');


                Route::post('/', [
                    OrganizationController::class,
                    'store'
                ])->middleware('permission:organizations.create')
                    ->name('store');


                Route::get('/trash', [
                    OrganizationController::class,
                    'trash'
                ])->middleware('permission:organizations.delete')
                    ->name('trash');


                Route::post('/{id}/restore', [
                    OrganizationController::class,
                    'restore'
                ])->middleware('permission:organizations.delete')
                    ->name('restore');


                Route::get('/{organization}/edit', [
                    OrganizationController::class,
                    'edit'
                ])->middleware('permission:organizations.edit')
                    ->name('edit');


                Route::put('/{organization}', [
                    OrganizationController::class,
                    'update'
                ])->middleware('permission:organizations.edit')
                    ->name('update');


                Route::delete('/{organization}', [
                    OrganizationController::class,
                    'destroy'
                ])->middleware('permission:organizations.delete')
                    ->name('destroy');


                Route::get('/{organization}', [
                    OrganizationController::class,
                    'show'
                ])->middleware('permission:organizations.view')
                    ->name('show');

            });


        // ==================================================
        // ROLE MANAGEMENT
        // ==================================================

        Route::prefix('roles')
            ->name('roles.')
            ->middleware(['auth:superadmin', '2fa'])
            ->group(function () {

                Route::get('/', [
                    RoleController::class,
                    'index'
                ])->middleware('permission:roles.view')
                    ->name('index');


                Route::get('/create', [
                    RoleController::class,
                    'create'
                ])->middleware('permission:roles.create')
                    ->name('create');


                Route::post('/', [
                    RoleController::class,
                    'store'
                ])->middleware('permission:roles.create')
                    ->name('store');


                Route::get('/trash', [
                    RoleController::class,
                    'trash'
                ])->middleware('permission:roles.delete')
                    ->name('trash');


                Route::post('/{id}/restore', [
                    RoleController::class,
                    'restore'
                ])->middleware('permission:roles.delete')
                    ->name('restore');


                Route::get('/{role}/edit', [
                    RoleController::class,
                    'edit'
                ])->middleware('permission:roles.edit')
                    ->name('edit');


                Route::put('/{role}', [
                    RoleController::class,
                    'update'
                ])->middleware('permission:roles.edit')
                    ->name('update');


                Route::delete('/{role}', [
                    RoleController::class,
                    'destroy'
                ])->middleware('permission:roles.delete')
                    ->name('destroy');

            });


        // ==================================================
        // ADMIN MANAGEMENT
        // ==================================================

        Route::prefix('admins')
            ->name('admins.')
            ->middleware(['auth:superadmin', '2fa'])
            ->group(function () {

                Route::get('/', [
                    AdminController::class,
                    'index'
                ])->middleware('permission:admins.view')
                    ->name('index');


                Route::get('/create', [
                    AdminController::class,
                    'create'
                ])->middleware('permission:admins.create')
                    ->name('create');


                Route::post('/', [
                    AdminController::class,
                    'store'
                ])->middleware('permission:admins.create')
                    ->name('store');


                Route::get('/trash', [
                    AdminController::class,
                    'trash'
                ])->middleware('permission:admins.delete')
                    ->name('trash');


                Route::post('/{id}/restore', [
                    AdminController::class,
                    'restore'
                ])->middleware('permission:admins.delete')
                    ->name('restore');


                Route::get('/{admin}/edit', [
                    AdminController::class,
                    'edit'
                ])->middleware('permission:admins.edit')
                    ->name('edit');


                Route::put('/{admin}', [
                    AdminController::class,
                    'update'
                ])->middleware('permission:admins.edit')
                    ->name('update');


                Route::delete('/{admin}', [
                    AdminController::class,
                    'destroy'
                ])->middleware('permission:admins.delete')
                    ->name('destroy');

            });

    });


// ======================================================
// MULTIPLE ACCOUNT SWITCHER
// ======================================================

Route::middleware('auth')->group(function () {

    Route::post('/accounts/add', [
        AccountSwitcherController::class,
        'addAccount'
    ])->name('accounts.add');


    Route::post('/accounts/switch/{id}', [
        AccountSwitcherController::class,
        'switchAccount'
    ])->name('accounts.switch');


    Route::delete('/accounts/remove/{id}', [
        AccountSwitcherController::class,
        'removeAccount'
    ])->name('accounts.remove');

});


// Authentication Routes
require __DIR__.'/auth.php';
