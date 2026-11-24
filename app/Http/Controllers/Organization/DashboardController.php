<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('organization')->user();

        $organization = $user->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->firstOrFail();

        return view('organization.dashboard', compact(
            'organization',
            'user'
        ));
    }
}