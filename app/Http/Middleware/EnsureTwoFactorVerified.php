<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->role === 'super_admin') {

            if (!$user->google2fa_enabled) {
                return redirect()->route('admin.2fa.setup');
            }

            if (!session('admin_2fa_verified')) {
                return redirect()->route('admin.2fa.verify');
            }
        }

        return $next($request);
    }
}
