<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    // Show 2FA settings
    public function settings()
    {
        $user = Auth::guard('superadmin')->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403);
        }

        return view('admin.two-factor.settings', compact('user'));
    }

    // Show 2FA setup page
    public function setup()
    {
        $user = Auth::guard('superadmin')->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403);
        }

        if ($user->google2fa_enabled) {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->google2fa_secret) {
            $google2fa = new Google2FA();
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $google2fa = new Google2FA();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        return view('admin.two-factor.setup', compact('qrCodeUrl'));
    }

    // Enable Google Authenticator
    public function enable(Request $request)
    {
        $user = Auth::guard('superadmin')->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403);
        }

        if ($user->google2fa_enabled || !$user->google2fa_secret) {
            abort(403);
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->google2fa_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Invalid authenticator code. Please try again.',
            ]);
        }

        $user->google2fa_enabled = true;
        $user->save();

        $request->session()->regenerate();

        $request->session()->put('admin_2fa_verified', true);
        $request->session()->forget('admin_2fa_pending');

        $this->recordActivity(
            $request,
            $user,
            '2FA Enabled',
            'Google Authenticator was successfully enabled.'
        );

        $this->recordActivity(
            $request,
            $user,
            'Login',
            'Super Admin successfully logged in after enabling Google Authenticator.'
        );

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Google Authenticator enabled successfully.');
    }

    // Disable Google Authenticator
    public function disable(Request $request)
    {
        $user = Auth::guard('superadmin')->user();

        if (!$user || $user->role !== 'super_admin') {
            abort(403);
        }

        if (!$user->google2fa_enabled || !$user->google2fa_secret) {
            return back()->with('error', 'Two-factor authentication is not enabled.');
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->google2fa_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Invalid authenticator code. Please try again.',
            ]);
        }

        $user->google2fa_enabled = false;
        $user->google2fa_secret = null;
        $user->save();

        $request->session()->forget([
            'admin_2fa_pending',
            'admin_2fa_verified',
        ]);

        $this->recordActivity(
            $request,
            $user,
            '2FA Disabled',
            'Google Authenticator was disabled after successful code verification.'
        );

        return redirect()
            ->route('admin.2fa.settings')
            ->with('success', 'Google Authenticator disabled successfully.');
    }

    // Show verification page
    public function showVerify()
    {
        $user = Auth::guard('superadmin')->user();

        if (
            !$user ||
            $user->role !== 'super_admin' ||
            !$user->google2fa_enabled ||
            !session('admin_2fa_pending')
        ) {
            abort(403);
        }

        return view('admin.two-factor.verify');
    }

    // Verify Google Authenticator code
    public function verify(Request $request)
    {
        $user = Auth::guard('superadmin')->user();

        if (
            !$user ||
            $user->role !== 'super_admin' ||
            !$user->google2fa_enabled ||
            !session('admin_2fa_pending')
        ) {
            abort(403);
        }

        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey(
            $user->google2fa_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Invalid authenticator code. Please try again.',
            ]);
        }

        $request->session()->regenerate();

        $request->session()->put('admin_2fa_verified', true);
        $request->session()->forget('admin_2fa_pending');

        $this->recordActivity(
            $request,
            $user,
            'Login',
            'Super Admin successfully logged in using Google Authenticator.'
        );

        return redirect()->route('admin.dashboard');
    }

    // Save activity record
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
