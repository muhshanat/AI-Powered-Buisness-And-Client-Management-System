<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
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

        session(['admin_2fa_verified' => true]);
        session()->forget('admin_2fa_pending');

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Google Authenticator enabled successfully.');
    }

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

        session(['admin_2fa_verified' => true]);
        session()->forget('admin_2fa_pending');

        return redirect()->route('admin.dashboard');
    }
}
