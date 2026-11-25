
@extends('organization.layouts.app')

@section('title', 'Products')
@section('heading', 'Products')

@push('styles')
<style>
    .connection-heading { margin-bottom: 24px; }
    .connection-heading h1 { font-size: 23px; color: #153b60; margin-bottom: 7px; }
    .connection-heading p { color: #71869d; font-size: 13px; }

    .connection-card {
        background: #fff;
        border: 1px solid #d5e0ec;
        border-radius: 13px;
        padding: 25px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(30,65,100,.05);
    }

    .connection-card-header,
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }

    .connection-card-header h2,
    .sync-info h2,
    .products-header h2 {
        font-size: 17px;
        color: #153b60;
    }

    .connection-card-header p,
    .products-header p {
        color: #71869d;
        font-size: 12px;
        margin-top: 5px;
    }

    .connection-status {
        padding: 7px 13px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #fff4df;
        color: #b7791f;
    }

    .connection-status.connected {
        background: #e4f8ed;
        color: #16834b;
    }

    .connection-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 19px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.full { grid-column: 1 / -1; }

    .form-group label {
        font-size: 12px;
        font-weight: 700;
        color: #405b78;
    }

    .form-group input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d5e0ec;
        border-radius: 8px;
        outline: none;
        font-size: 13px;
        color: #153451;
        background: #fff;
    }

    .form-group input:focus {
        border-color: #3287d8;
        box-shadow: 0 0 0 3px rgba(50,135,216,.10);
    }

    .form-hint { font-size: 11px; color: #8498ad; }

    .primary-btn,
    .sync-button {
        border: none;
        background: #287bd1;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .primary-btn:hover,
    .sync-button:hover { background: #1769b0; }

    .sync-button:disabled {
        cursor: not-allowed;
        opacity: .6;
    }

    .security-note {
        background: #eff6fd;
        border: 1px solid #d6e8fa;
        border-radius: 9px;
        padding: 13px 15px;
        margin-top: 20px;
        color: #476783;
        font-size: 12px;
        line-height: 1.6;
    }

    .sync-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .sync-info h2 { margin-bottom: 7px; }

    .sync-info p {
        font-size: 12px;
        color: #71869d;
        line-height: 1.7;
    }

    .sync-button { white-space: nowrap; }

    .alert-message {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .success-message { background: #e4f8ed; color: #16834b; }
    .error-message { background: #fff0f0; color: #c03945; }

    .product-count {
        background: #eff6fd;
        color: #287bd1;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .table-wrapper { overflow-x: auto; }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    .products-table th {
        background: #f3f7fb;
        color: #526b85;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid #e1e9f1;
    }

    .products-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #edf1f5;
        color: #405b78;
        font-size: 12px;
        vertical-align: middle;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 200px;
    }

    .product-image {
        width: 43px;
        height: 43px;
        border-radius: 7px;
        object-fit: cover;
        background: #f1f5f9;
        border: 1px solid #e4ebf2;
        flex-shrink: 0;
    }

    .product-name {
        color: #153b60;
        font-weight: 700;
        font-size: 12px;
    }

    .product-sku {
        color: #8498ad;
        font-size: 11px;
        margin-top: 4px;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 15px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-active { background: #e4f8ed; color: #16834b; }
    .status-inactive { background: #fff0f0; color: #c03945; }

    .empty-products {
        text-align: center;
        padding: 45px 15px;
        color: #8498ad;
    }

    .empty-products strong {
        display: block;
        color: #405b78;
        font-size: 14px;
        margin-bottom: 7px;
    }

    .empty-products p { font-size: 12px; }

    .pagination-area { margin-top: 20px; }

    .pagination-area svg {
        width: 16px !important;
        height: 16px !important;
        max-width: 16px !important;
        display: inline-block;
    }

    .pagination-area nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .pagination-area a,
    .pagination-area span { font-size: 12px; }

    @media(max-width:650px) {
        .connection-form { grid-template-columns: 1fr; }
        .form-group.full { grid-column: auto; }
        .sync-card { align-items: flex-start; flex-direction: column; }
        .connection-card-header,
        .products-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<div class="connection-heading">
    <h1>Connect Your Website</h1>
    <p>Connect your existing website API to import and synchronize your products.</p>
</div>

@if(session('success'))
    <div class="alert-message success-message">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-message error-message">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

{{-- Website API Configuration --}}
<div class="connection-card">

    <div class="connection-card-header">
        <div>
            <h2>Website API Configuration</h2>
            <p>Enter your website API credentials below.</p>
        </div>

        <span class="connection-status {{ $connection && $connection->is_connected ? 'connected' : '' }}">
            {{ $connection && $connection->is_connected ? '● Connected' : '● Not Connected' }}
        </span>
    </div>

    <form method="POST" action="{{ route('organization.website-connection.store') }}">
        @csrf

        <div class="connection-form">

            <div class="form-group">
                <label>Website Name</label>
                <input
                    type="text"
                    name="website_name"
                    placeholder="e.g. My Online Store"
                    value="{{ old('website_name', $connection?->website_name) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Website API URL</label>
                <input
                    type="url"
                    name="api_url"
                    placeholder="https://example.com/api/products"
                    value="{{ old('api_url', $connection?->api_url) }}"
                    required
                >
            </div>

            <div class="form-group full">
                <label>Bearer Token</label>
                <input
                    type="password"
                    name="bearer_token"
                    placeholder="{{ $connection ? 'Enter a new token to update credentials' : 'Enter your API Bearer Token' }}"
                    required
                    autocomplete="new-password"
                >

                <span class="form-hint">
                    Your token is encrypted before being stored.
                    @if($connection)
                        A new token is required when updating credentials.
                    @endif
                </span>
            </div>

            <div class="form-group full">
                <button type="submit" class="primary-btn">
                    {{ $connection ? 'Update & Connect Website' : 'Connect Website' }}
                </button>
            </div>

        </div>
    </form>

    <div class="security-note">
        <strong>Security Notice</strong><br>
        Your API credentials are stored securely.
        TanovaFlow uses your Bearer Token to communicate with your website API.
    </div>

</div>


{{-- Product Synchronization --}}
<div class="connection-card">

    <div class="sync-card">

        <div class="sync-info">
            <h2>Product Synchronization</h2>

            <p>
                Import new products and update existing product information
                whenever you choose.
            </p>

            <p>
                Last Sync:
                <strong>
                    {{ $connection?->last_synced_at?->format('d M Y, h:i A') ?? 'Not synced yet' }}
                </strong>
            </p>
        </div>

        @if($connection && $connection->is_connected)
            <button
                type="button"
                id="startSyncButton"
                class="sync-button"
            >
                ↻ Sync Products
            </button>
        @else
            <button type="button" class="sync-button" disabled>
                ↻ Sync Products
            </button>
        @endif

    </div>

    {{-- Synchronization Progress --}}
    <div id="syncProgressSection" style="display:none; margin-top:25px;">

        <div style="display:flex; justify-content:space-between; margin-bottom:10px; gap:10px;">

            <strong id="syncStatusText" style="font-size:13px; color:#153b60;">
                Preparing synchronization...
            </strong>

            <strong id="syncPercentage" style="font-size:13px; color:#287bd1;">
                0%
            </strong>

        </div>

        <div style="width:100%; height:10px; background:#e8eef5; border-radius:20px; overflow:hidden;">

            <div
                id="syncProgressBar"
                style="width:0%; height:100%; background:#287bd1; border-radius:20px; transition:width .3s;"
            ></div>

        </div>

        <p id="syncCountText" style="font-size:12px; color:#71869d; margin-top:10px;">
            0 products synchronized
        </p>

    </div>

    <div id="syncResultMessage" style="margin-top:15px; font-size:13px;"></div>

    <form id="syncStartForm" style="display:none;">
        @csrf
    </form>

</div>


{{-- Synced Products Table --}}
<div class="connection-card">

    <div class="products-header">

        <div>
            <h2>▣ Synced Products</h2>
            <p>Products imported from your connected website.</p>
        </div>

        <span class="product-count" id="productCount">
            {{ $products->total() }} Products
        </span>

    </div>

    {{-- Always keep the table available for JavaScript updates --}}
    <div class="table-wrapper" id="productsTableContainer">

        <table class="products-table">

            <thead>
                <tr>
                    <th>PRODUCT</th>
                    <th>SKU</th>
                    <th>PRICE</th>
                    <th>STOCK</th>
                    <th>STATUS</th>
                </tr>
            </thead>

            <tbody id="productsTableBody">

                @forelse($products as $product)

                    <tr>

                        <td>
                            <div class="product-info">

                                @if($product->image)
                                    <img
                                        src="{{ $product->image }}"
                                        alt="{{ $product->name }}"
                                        class="product-image"
                                    >
                                @else
                                    <div class="product-image"></div>
                                @endif

                                <div>
                                    <div class="product-name">
                                        {{ $product->name }}
                                    </div>

                                    <div class="product-sku">
                                        {{ $product->brand ?? 'No brand' }}
                                    </div>
                                </div>

                            </div>
                        </td>

                        <td>{{ $product->sku ?? '—' }}</td>

                        <td>
                            ৳{{ number_format((float) $product->price, 2) }}
                        </td>

                        <td>{{ $product->stock }}</td>

                        <td>
                            @if($product->source_published && !$product->is_draft)
                                <span class="status-badge status-active">Published</span>
                            @else
                                <span class="status-badge status-inactive">Unpublished</span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">
                            <div class="empty-products">
                                <strong>No Products Synced Yet</strong>
                                <p>Connect your website and click Sync Products to import products here.</p>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($products->total() > 0)
        <div class="pagination-area">
            {{ $products->links() }}
        </div>
    @endif

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const startButton = document.getElementById('startSyncButton');

    if (!startButton) return;

    const progressSection = document.getElementById('syncProgressSection');
    const progressBar = document.getElementById('syncProgressBar');
    const percentageText = document.getElementById('syncPercentage');
    const statusText = document.getElementById('syncStatusText');
    const countText = document.getElementById('syncCountText');
    const resultMessage = document.getElementById('syncResultMessage');

    const productsTableBody = document.getElementById('productsTableBody');
    const productCount = document.getElementById('productCount');

    const csrfToken = document.querySelector(
        '#syncStartForm input[name="_token"]'
    ).value;

    const startUrl = @json(route('organization.website-connection.sync'));
    const batchUrl = @json(route('organization.website-connection.sync-batch'));
    const productsUrl = @json(route('organization.website-connection.synced-products'));

    let isRunning = false;

    function delay(milliseconds) {
        return new Promise(resolve => setTimeout(resolve, milliseconds));
    }

    async function sendRequest(url) {

        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

    }


    // Refresh the table after each successful batch.
    async function refreshProductsTable() {

        const response = await fetch(productsUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error('Could not refresh the products table.');
        }

        productCount.textContent =
            Number(data.total).toLocaleString() + ' Products';

        productsTableBody.replaceChildren();

        if (!data.products || data.products.length === 0) {

            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 5;

            const empty = document.createElement('div');
            empty.className = 'empty-products';

            const heading = document.createElement('strong');
            heading.textContent = 'No Products Synced Yet';

            const paragraph = document.createElement('p');
            paragraph.textContent =
                'Connect your website and click Sync Products to import products here.';

            empty.append(heading, paragraph);
            cell.appendChild(empty);
            row.appendChild(cell);
            productsTableBody.appendChild(row);

            return;
        }

        data.products.forEach(function (product) {

            const row = document.createElement('tr');

            // Product name and image
            const productCell = document.createElement('td');

            const productInfo = document.createElement('div');
            productInfo.className = 'product-info';

            if (product.image) {

                const image = document.createElement('img');
                image.src = product.image;
                image.alt = product.name || 'Product';
                image.className = 'product-image';

                productInfo.appendChild(image);

            } else {

                const imagePlaceholder = document.createElement('div');
                imagePlaceholder.className = 'product-image';

                productInfo.appendChild(imagePlaceholder);
            }

            const details = document.createElement('div');

            const name = document.createElement('div');
            name.className = 'product-name';
            name.textContent = product.name || 'Unnamed Product';

            const brand = document.createElement('div');
            brand.className = 'product-sku';
            brand.textContent = product.brand || 'No brand';

            details.append(name, brand);
            productInfo.appendChild(details);
            productCell.appendChild(productInfo);

            // SKU
            const skuCell = document.createElement('td');
            skuCell.textContent = product.sku || '—';

            // Price
            const priceCell = document.createElement('td');

            const price = Number(product.price || 0);

            priceCell.textContent =
                '৳' + price.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            // Stock
            const stockCell = document.createElement('td');
            stockCell.textContent = product.stock ?? 0;

            // Status
            const statusCell = document.createElement('td');

            const statusBadge = document.createElement('span');

            const published =
                Boolean(product.source_published) &&
                !Boolean(product.is_draft);

            statusBadge.className = published
                ? 'status-badge status-active'
                : 'status-badge status-inactive';

            statusBadge.textContent = published
                ? 'Published'
                : 'Unpublished';

            statusCell.appendChild(statusBadge);

            row.append(
                productCell,
                skuCell,
                priceCell,
                stockCell,
                statusCell
            );

            productsTableBody.appendChild(row);

        });

    }


    // Process one batch at a time.
    async function processBatch() {

        let retries = 0;

        while (true) {

            const response = await sendRequest(batchUrl);

            if (response.status === 429) {

                retries++;

                if (retries > 5) {
                    throw new Error('API rate limit reached too many times.');
                }

                statusText.textContent =
                    'API rate limit reached. Waiting before retry...';

                const errorData = await response.json().catch(() => ({}));

                const retryAfter = Number(
                    errorData.retry_after ||
                    response.headers.get('Retry-After') ||
                    10
                );

                await delay(
                    Math.max(2000, Math.min(retryAfter * 1000, 60000))
                );

                continue;
            }

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || 'Batch synchronization failed.'
                );
            }

            // Update progress.
            const total = Number(data.total);
            const processed = Number(data.processed);

            const percentage = total > 0
                ? Math.min(100, Math.round((processed / total) * 100))
                : 100;

            progressBar.style.width = percentage + '%';
            percentageText.textContent = percentage + '%';

            countText.textContent =
                processed.toLocaleString() + ' / ' +
                total.toLocaleString() +
                ' products synchronized';

            // Refresh the product table immediately after this batch.
            await refreshProductsTable();

            if (data.done) {

                statusText.textContent = 'Synchronization completed!';

                resultMessage.style.color = '#16834b';
                resultMessage.textContent =
                    processed.toLocaleString() +
                    ' products synchronized successfully.';

                startButton.disabled = false;
                startButton.textContent = '↻ Sync Products';

                isRunning = false;

                // Reload to update pagination and last sync date.
                setTimeout(function () {
                    window.location.reload();
                }, 1500);

                return;
            }

            statusText.textContent =
                'Batch synchronized. Fetching the next 10 products...';

            await delay(1000);

            break;
        }

        if (isRunning) {
            await processBatch();
        }

    }


    // Start synchronization.
    startButton.addEventListener('click', async function () {

        if (isRunning) return;

        const confirmed = confirm(
            'Start product synchronization from your connected website?'
        );

        if (!confirmed) return;

        isRunning = true;

        startButton.disabled = true;
        startButton.textContent = 'Synchronizing...';

        progressSection.style.display = 'block';

        resultMessage.textContent = '';

        progressBar.style.width = '0%';
        percentageText.textContent = '0%';

        statusText.textContent = 'Starting synchronization...';
        countText.textContent = 'Preparing products...';

        try {

            const startResponse = await sendRequest(startUrl);

            const startData = await startResponse.json();

            if (!startResponse.ok) {
                throw new Error(
                    startData.message || 'Could not start synchronization.'
                );
            }

            await processBatch();

        } catch (error) {

            statusText.textContent = 'Synchronization stopped.';

            resultMessage.style.color = '#c03945';
            resultMessage.textContent = error.message;

            startButton.disabled = false;
            startButton.textContent = '↻ Retry Sync';

            isRunning = false;

        }

    });

});
</script>
@endpush

@endsection
