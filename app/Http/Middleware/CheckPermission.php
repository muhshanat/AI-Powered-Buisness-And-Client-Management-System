<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {

        // Get the logged-in platform administrator
        $user = $request->user('superadmin');

        if (!$user) {
            abort(403, 'Please log in first.');
        }

        // Super Admin has full access
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Check permissions for regular Admin
        $role = Role::where('name', $user->role)->first();

        if (!$role) {
            abort(403, 'No matching role has been assigned to your account.');
        }

        $permissions = $role->permissions ?? [];

        if (!in_array($permission, $permissions)) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
