<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('superadmin')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Regular Admin can continue without Super Admin 2FA
        if ($user->role === 'Admin') {
            return $next($request);
        }

        // Only Super Admin must complete 2FA
        if ($user->role !== 'super_admin') {
            abort(403, 'Unauthorized access.');
        }

        if (!$user->google2fa_enabled) {
            return redirect()->route('admin.2fa.setup');
        }

        if (!$request->session()->get('admin_2fa_verified')) {
            return redirect()->route('admin.2fa.verify');
        }

        return $next($request);
    }
}
