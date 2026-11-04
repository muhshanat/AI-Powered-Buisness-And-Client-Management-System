<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
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

            // Super Admin Login
            if ($user->role === 'super_admin') {

                if (!$user->google2fa_enabled) {

                    $this->recordActivity(
                        $request,
                        $user,
                        '2FA Setup Started',
                        'Super Admin password verified. Google Authenticator setup required.'
                    );

                    $request->session()->forget('admin_2fa_verified');

                    return redirect()->route('admin.2fa.setup');
                }

                $this->recordActivity(
                    $request,
                    $user,
                    '2FA Challenge',
                    'Super Admin password verified. Google Authenticator verification required.'
                );

                $request->session()->forget('admin_2fa_verified');
                $request->session()->put('admin_2fa_pending', true);

                return redirect()->route('admin.2fa.verify');
            }

            // Regular Admin Login
            if ($user->role === 'Admin') {

                $this->recordActivity(
                    $request,
                    $user,
                    'Login',
                    'Admin successfully logged in.'
                );

                return redirect()->route('admin.dashboard');
            }

            // Reject users who do not have admin access
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
        $user = Auth::user();

        if ($user && in_array($user->role, ['Admin', 'super_admin'])) {

            $this->recordActivity(
                $request,
                $user,
                'Logout',
                'Admin successfully logged out.'
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Save activity in activity_logs table.
     */
    private function recordActivity(
        Request $request,
        User $user,
        string $action,
        string $description
    ): void {
        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $action,
            'module' => 'Authentication',
            'description' => $description,
            'ip_address' => $request->ip(),
        ]);
    }
}
