<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'organization') {
            abort(403, 'You are not authorized to access this workspace.');
        }

        $organization = $user->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->first();

        if (!$organization) {
            abort(403, 'Your organization is not approved or membership is inactive.');
        }

        return $next($request);
    }
}
