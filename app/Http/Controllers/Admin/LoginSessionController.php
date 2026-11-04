<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginSessionController extends Controller
{
    public function index()
    {
       $sessions = DB::table('sessions')
    ->whereNotNull('sessions.user_id')
    ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'users.name',
                'users.email'
            )
            ->orderByDesc('sessions.last_activity')
            ->paginate(15);

        return view('admin.sessions.index', compact('sessions'));
    }

   public function destroy($id)
{
    $currentSessionId = request()->session()->getId();

    // Never terminate the current session
    if ($id === $currentSessionId) {
        return back()->with('error', 'You cannot terminate your current session.');
    }

    // Find exactly one session
    $session = DB::table('sessions')
        ->where('id', $id)
        ->first();

    if (!$session) {
        return back()->with('error', 'Session not found.');
    }

    // Delete only the selected session ID
    DB::table('sessions')
        ->where('id', '=', $id)
        ->limit(1)
        ->delete();

    return back()->with('success', 'Selected login session terminated successfully.');
}


public function destroyOthers()
{
    $currentUser = auth()->user();
    $currentSessionId = request()->session()->getId();

    $deleted = DB::table('sessions')
        ->where('user_id', $currentUser->id)
        ->where('id', '!=', $currentSessionId)
        ->delete();

    return back()->with(
        'success',
        $deleted . ' other session(s) terminated successfully.'
    );
}

}

