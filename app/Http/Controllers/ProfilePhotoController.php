<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = auth()->user();

        // Delete previous uploaded picture
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new picture
        $path = $request->file('profile_photo')
            ->store('profile-photos', 'public');

        $user->profile_photo_path = $path;
        $user->save();

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function destroy()
    {
        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = null;
        $user->save();

        return back()->with('success', 'Profile picture removed successfully.');
    }
}