<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    private function currentOrganization()
    {
        return auth('organization')->user()
            ->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->firstOrFail();
    }

    public function index()
    {
        $organization = $this->currentOrganization();

        $services = $organization->services()
            ->latest()
            ->paginate(10);

        return view('organization.services.index', compact(
            'organization',
            'services'
        ));
    }

    public function create()
    {
        return view('organization.services.create');
    }

    public function store(Request $request)
    {
        $organization = $this->currentOrganization();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('services', 'public');
        }

        $organization->services()->create($validated);

        return redirect()
            ->route('organization.services.index')
            ->with('success', 'Service added successfully.');
    }

    public function edit(Service $service)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $service->organization_id === $organization->id,
            404
        );

        return view('organization.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $service->organization_id === $organization->id,
            404
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $validated['image'] = $request->file('image')
                ->store('services', 'public');
        }

        $service->update($validated);

        return redirect()
            ->route('organization.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $service->organization_id === $organization->id,
            404
        );

        $service->delete();

        return redirect()
            ->route('organization.services.index')
            ->with('success', 'Service moved to trash.');
    }
}
