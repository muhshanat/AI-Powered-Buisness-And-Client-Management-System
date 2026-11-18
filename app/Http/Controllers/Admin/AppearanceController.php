<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.appearance', compact('settings'));
    }

   
public function update(Request $request)
{
    $validated = $request->validate([
        'application_name' => ['required', 'string', 'max:100'],
        'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],

        'logo_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'secondary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'page_background' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'nav_background' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'nav_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'button_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'heading_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'body_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    ]);

    // Handle logo upload separately
    $logo = $validated['logo'] ?? null;
    unset($validated['logo']);

    foreach ($validated as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    // Save uploaded logo
    if ($logo) {
        $oldLogo = Setting::where('key', 'logo_path')->value('value');

        $logoPath = $logo->store('logos', 'public');

        Setting::updateOrCreate(
            ['key' => 'logo_path'],
            ['value' => $logoPath]
        );

        // Delete previous logo after successful upload
        if ($oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }
    }

    return redirect()
        ->route('admin.appearance.index')
        ->with('success', 'Appearance settings updated successfully.');
}


    public function reset()
{
    $defaults = [
        'application_name' => 'TanovaFlow',
        'logo_text_color' => '#10263d',
        'primary_color' => '#185f9b',
        'secondary_color' => '#117f83',
        'page_background' => '#e1eaf3',
        'nav_background' => '#ffffff',
        'nav_text_color' => '#263b52',
        'button_color' => '#185f9b',
        'heading_color' => '#10263d',
        'body_text_color' => '#263b52',
    ];

    foreach ($defaults as $key => $value) {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    return redirect()
        ->route('admin.appearance.index')
        ->with('success', 'Appearance settings restored to defaults.');
}
}
