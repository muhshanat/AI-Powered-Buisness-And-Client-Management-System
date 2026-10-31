<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'super_admin') {

                if (!$user->google2fa_enabled) {
                    $request->session()->forget('admin_2fa_verified');

                    return redirect()->route('admin.2fa.setup');
                }

                $request->session()->forget('admin_2fa_verified');
                $request->session()->put('admin_2fa_pending', true);

                return redirect()->route('admin.2fa.verify');
            }

            if ($user->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or you do not have admin access.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
