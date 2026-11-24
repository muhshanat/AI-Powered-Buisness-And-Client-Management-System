<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
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

        $products = $organization->products()
            ->latest()
            ->paginate(10);

        return view('organization.products.index', compact(
            'organization',
            'products'
        ));
    }

    public function create()
    {
        return view('organization.products.create');
    }

    public function store(Request $request)
    {
        $organization = $this->currentOrganization();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $organization->products()->create($validated);

        return redirect()
            ->route('organization.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $product->organization_id === $organization->id,
            404
        );

        return view('organization.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $product->organization_id === $organization->id,
            404
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('organization.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $product->organization_id === $organization->id,
            404
        );

        $product->delete();

        return redirect()
            ->route('organization.products.index')
            ->with('success', 'Product moved to trash.');
    }
}
