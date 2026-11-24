<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // Show organization login page
    public function create(): View
    {
        return view('auth.login');
    }

    // Handle organization login
   public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->route('organization.dashboard');
}

    // Logout organization account only
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('organization')->logout();

        // Do not invalidate the entire session.
        return redirect('/');
    }
}
