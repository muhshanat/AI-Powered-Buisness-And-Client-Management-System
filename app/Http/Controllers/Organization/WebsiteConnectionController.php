<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\WebsiteConnection;
use App\Models\Product;

class WebsiteConnectionController extends Controller
{
    /**
     * Get the currently authenticated organization.
     */
    private function currentOrganization()
    {
        $user = Auth::guard('organization')->user();

        return $user->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->firstOrFail();
    }

    /**
     * Display website connection and synchronized products.
     */
    public function index()
    {
        $organization = $this->currentOrganization();

        $connection = $organization->websiteConnection;

        $products = Product::where('organization_id', $organization->id)
            ->latest()
            ->paginate(10);

        return view(
            'organization.website-connection.index',
            compact('connection', 'products')
        );
    }

    /**
     * Connect or update the external website.
     */
    public function store(Request $request)
    {
        $organization = $this->currentOrganization();

        $validated = $request->validate([
            'website_name' => ['required', 'string', 'max:255'],
            'api_url' => ['required', 'url', 'max:2048'],
            'bearer_token' => ['required', 'string', 'max:5000'],
        ]);

        try {
            $response = Http::withToken($validated['bearer_token'])
                ->acceptJson()
                ->timeout(20)
                ->get($validated['api_url']);

            if (!$response->successful()) {
                return back()
                    ->withErrors([
                        'api_url' => 'API connection failed. HTTP status: '
                            . $response->status()
                    ])
                    ->withInput($request->except('bearer_token'));
            }

            if (!is_array($response->json())) {
                return back()
                    ->withErrors([
                        'api_url' => 'The website did not return valid JSON data.'
                    ])
                    ->withInput($request->except('bearer_token'));
            }

            $organization->websiteConnection()->updateOrCreate(
                ['organization_id' => $organization->id],
                [
                    'website_name' => $validated['website_name'],
                    'api_url' => $validated['api_url'],
                    'bearer_token' => $validated['bearer_token'],
                    'is_connected' => true,
                ]
            );

            return redirect()
                ->route('organization.website-connection.index')
                ->with('success', 'Website connected successfully!');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            Log::warning('Website API connection failed', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors([
                    'api_url' => 'Could not reach the website API. Check the URL and try again.'
                ])
                ->withInput($request->except('bearer_token'));
        }
    }

    /**
     * Start a new product synchronization session.
     */
   

public function sync()
{
    $organization = $this->currentOrganization();

    $connection = $organization->websiteConnection;

    if (!$connection || !$connection->is_connected) {
        return response()->json([
            'success' => false,
            'message' => 'Please connect your website first.',
        ], 400);
    }

    if ($connection->sync_status === 'running') {
        return response()->json([
            'success' => false,
            'message' => 'Synchronization is already running.',
        ], 409);
    }

    $connection->update([
        'sync_page' => 1,
        'sync_total' => 0,
        'sync_processed' => 0,
        'sync_status' => 'running',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Synchronization started.',
    ]);
}

    /**
     * Process one batch of products.
     */
    public function syncBatch()
    {
        $organization = $this->currentOrganization();

        $connection = $organization->websiteConnection;

        if (!$connection || !$connection->is_connected) {
            return response()->json([
                'success' => false,
                'message' => 'Website is not connected.',
            ], 400);
        }

        if ($connection->sync_status !== 'running') {
            return response()->json([
                'success' => false,
                'message' => 'No active synchronization found.',
            ], 400);
        }

        // TEMPORARY TEST: Fetch only one product.
        // Change to 50 after the one-product test succeeds.
        $perPage = 1;

        try {
            $response = Http::withToken($connection->bearer_token)
                ->acceptJson()
                ->timeout(60)
                ->get($connection->api_url, [
                    'page' => $connection->sync_page,
                    'per_page' => $perPage,
                ]);

            // API rate-limit response.
            if ($response->status() === 429) {
                return response()->json([
                    'success' => false,
                    'retry' => true,
                    'message' => 'API rate limit reached. Retrying shortly.',
                    'retry_after' => (int) (
                        $response->header('Retry-After') ?? 10
                    ),
                ], 429);
            }

            if (!$response->successful()) {
                throw new \RuntimeException(
                    'API request failed: HTTP ' . $response->status()
                );
            }

            $data = $response->json();

            if (
                !is_array($data) ||
                !isset($data['products']) ||
                !is_array($data['products'])
            ) {
                throw new \RuntimeException('Invalid API response.');
            }

            $metadata = $data['metadata'] ?? [];

            $total = (int) ($metadata['total'] ?? 0);

            $productsInBatch = count($data['products']);

            if ($total < 0) {
                throw new \RuntimeException('Invalid product total.');
            }

            if ($productsInBatch === 0 && $total > 0) {
                throw new \RuntimeException(
                    'The API returned an empty product batch.'
                );
            }

            foreach ($data['products'] as $item) {

                if (!isset($item['id'])) {
                    continue;
                }

                DB::transaction(function () use (
                    $item,
                    $organization
                ) {

                    $sourceId = (string) $item['id'];

                    $title = $item['title'] ?? 'Unnamed Product';

                    $externalSlug = $item['slug']
                        ?? Str::slug($title);

                    $localSlug = Str::slug(
                        $externalSlug . '-' .
                        $organization->id . '-' .
                        $sourceId
                    );

                    $images = $item['images'] ?? [];

                    $firstImage = $images[0]['url'] ?? null;

                    $inventory = $item['inventory'] ?? [];

                    $stock = collect($inventory['stocks'] ?? [])
                        ->sum(fn ($stock) =>
                            (int) ($stock['quantity'] ?? 0)
                        );

                    $pricing = $item['pricing'] ?? [];

                    $productData = [
                        'organization_id' => $organization->id,
                        'name' => $title,
                        'slug' => $localSlug,
                        'description' => $item['description'] ?? null,
                        'summary' => $item['summary'] ?? null,

                        'price' => (float) (
                            $pricing['regular_price'] ?? 0
                        ),

                        'stock' => $stock,
                        'image' => $firstImage,

                        'status' =>
                            !empty($item['published']) &&
                            empty($item['is_draft'])
                                ? 'active'
                                : 'inactive',

                        'source_url' => $item['url'] ?? null,
                        'sku' => $item['sku'] ?? null,
                        'brand' => $item['brand'] ?? null,
                        'weight' => $item['weight'] ?? null,

                        'source_published' =>
                            $item['published'] ?? true,

                        'is_draft' => $item['is_draft'] ?? false,
                        'featured' => $item['featured'] ?? false,
                        'purchasable' => $item['purchasable'] ?? true,

                        'tags' => $item['tags'] ?? [],
                        'categories' => $item['categories'] ?? [],
                        'attributes' => $item['attributes'] ?? [],
                        'images' => $images,

                        'sale_prices' =>
                            $pricing['sale_prices'] ?? [],

                        'inventory_data' => $inventory,
                        'last_synced_at' => now(),
                    ];

                    // Find an existing product from this website.
                    $product = Product::withTrashed()
                        ->where('organization_id', $organization->id)
                        ->where('source_product_id', $sourceId)
                        ->first();

                    if ($product) {

                        $product->update($productData);

                        if ($product->trashed()) {
                            $product->restore();
                        }

                    } else {

                        $product = Product::create([
                            ...$productData,
                            'source_product_id' => $sourceId,
                        ]);
                    }

                    // Synchronize product variations.
                    foreach (
                        ($item['variations'] ?? []) as $index => $variation
                    ) {

                        $variantSourceId = (string) (
                            $variation['id']
                            ?? ($sourceId . '-' . $index)
                        );

                        $variantPricing =
                            $variation['pricing'] ?? [];

                        $variantInventory =
                            $variation['inventory'] ?? [];

                        $variantStock = collect(
                            $variantInventory['stocks'] ?? []
                        )->sum(fn ($stock) =>
                            (int) ($stock['quantity'] ?? 0)
                        );

                        $product->variants()->updateOrCreate(
                            [
                                'source_variant_id' => $variantSourceId,
                            ],
                            [
                                'title' => $variation['title'] ?? null,
                                'sku' => $variation['sku'] ?? null,
                                'weight' => $variation['weight'] ?? null,

                                'published' =>
                                    $variation['published'] ?? true,

                                'stock_status' =>
                                    $variantInventory['stock_status']
                                    ?? true,

                                'price' => (float) (
                                    $variantPricing['regular_price'] ?? 0
                                ),

                                'stock' => $variantStock,

                                'sale_prices' =>
                                    $variantPricing['sale_prices'] ?? [],

                                'attributes' =>
                                    $variation['attributes'] ?? [],

                                'images' =>
                                    $variation['images'] ?? [],

                                'inventory_data' => $variantInventory,
                                'last_synced_at' => now(),
                            ]
                        );
                    }
                });
            }

            // Update progress after this batch has been saved.
            $processed = $connection->sync_processed
                + $productsInBatch;

            $nextPage = $connection->sync_page + 1;

            $done = $processed >= $total
                || $productsInBatch === 0;

            $connection->update([
                'sync_total' => $total,
                'sync_processed' => $processed,
                'sync_page' => $nextPage,
                'sync_status' => $done ? 'completed' : 'running',
                'last_synced_at' => $done
                    ? now()
                    : $connection->last_synced_at,
            ]);

            return response()->json([
                'success' => true,
                'done' => $done,
                'processed' => $processed,
                'total' => $total,
                'next_page' => $nextPage,
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            Log::error('Batch synchronization connection failed', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not connect to the product API.',
            ], 500);

        } catch (\Throwable $e) {

            Log::error('Batch product synchronization failed', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);

            $connection->update([
                'sync_status' => 'failed',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Batch synchronization failed. Check Laravel logs.',
            ], 500);
        }
    }

 
    public function syncedProducts()
{
    $organization = $this->currentOrganization();

    $products = Product::where('organization_id', $organization->id)
        ->latest()
        ->take(10)
        ->get([
            'id',
            'name',
            'sku',
            'brand',
            'price',
            'stock',
            'image',
            'source_published',
            'is_draft',
        ]);

    $total = Product::where('organization_id', $organization->id)
        ->count();

    return response()->json([
        'success' => true,
        'products' => $products,
        'total' => $total,
    ]);
}


    }


