<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{

    // Show 2FA Settings
public function settings()
{
    $user = auth()->user();

    if (!$user || $user->role !== 'super_admin') {
        abort(403);
    }

    return view('admin.two-factor.settings', compact('user'));
}

    public function setup()
    {
        $user = auth()->user();

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
        $user = auth()->user();

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

        // Regenerate session after successful verification.
        $request->session()->regenerate();

        session(['admin_2fa_verified' => true]);
        session()->forget('admin_2fa_pending');

        // Record 2FA activation.
        $this->recordActivity(
            $request,
            $user,
            '2FA Enabled',
            'Google Authenticator was successfully enabled.'
        );

        // Record successful Super Admin login.
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
    $user = auth()->user();

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

    // Disable 2FA and remove the old secret
    $user->google2fa_enabled = false;
    $user->google2fa_secret = null;
    $user->save();

    // Clear pending 2FA verification state
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
        $user = auth()->user();

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
        $user = auth()->user();

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

        // Regenerate session after successful verification.
        $request->session()->regenerate();

        session(['admin_2fa_verified' => true]);
        session()->forget('admin_2fa_pending');

        // Record successful Super Admin login.
        $this->recordActivity(
            $request,
            $user,
            'Login',
            'Super Admin successfully logged in using Google Authenticator.'
        );

        return redirect()->route('admin.dashboard');
    }

    // Save activity record in database
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
