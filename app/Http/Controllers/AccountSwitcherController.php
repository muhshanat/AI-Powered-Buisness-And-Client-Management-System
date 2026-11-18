<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountSwitcherController extends Controller
{
    // Add another account
    public function addAccount(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $currentUser = Auth::user();

        $newUser = User::where('email', $request->email)->first();

        if (!$newUser || !Hash::check($request->password, $newUser->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ]);
        }

        $accounts = $request->session()->get('saved_accounts', []);

        // Save current account
        $accounts[$currentUser->id] = [
            'id' => $currentUser->id,
            'name' => $currentUser->name,
            'email' => $currentUser->email,
            'role' => $currentUser->role,
        ];

        // Save new account
        $accounts[$newUser->id] = [
            'id' => $newUser->id,
            'name' => $newUser->name,
            'email' => $newUser->email,
            'role' => $newUser->role,
        ];

        $request->session()->put('saved_accounts', $accounts);

        return back()->with('success', 'Account added successfully.');
    }

    // Switch account
    public function switchAccount(Request $request, $id)
    {
        $accounts = $request->session()->get('saved_accounts', []);

        if (!isset($accounts[$id])) {
            return back()->with('error', 'Account is not saved.');
        }

        $user = User::find($id);

        if (!$user) {
            unset($accounts[$id]);
            $request->session()->put('saved_accounts', $accounts);

            return back()->with('error', 'Account no longer exists.');
        }

        Auth::login($user);

        $request->session()->regenerate();

        // Reset previous account's 2FA verification
        $request->session()->forget([
            'admin_2fa_verified',
            'admin_2fa_pending',
        ]);

        if ($user->role === 'super_admin') {

            if ($user->google2fa_enabled) {
                $request->session()->put('admin_2fa_pending', true);

                return redirect()->route('admin.2fa.verify');
            }

            return redirect()->route('admin.2fa.setup');
        }

        return redirect()->route('dashboard');
    }

    // Remove saved account
    public function removeAccount(Request $request, $id)
    {
        $accounts = $request->session()->get('saved_accounts', []);

        unset($accounts[$id]);

        $request->session()->put('saved_accounts', $accounts);

        return back()->with('success', 'Account removed successfully.');
    }
}
