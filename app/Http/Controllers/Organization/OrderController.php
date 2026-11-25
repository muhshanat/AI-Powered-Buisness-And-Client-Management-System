<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    // Get the currently logged-in organization's approved account
    private function currentOrganization()
    {
        return auth('organization')->user()
            ->organizations()
            ->where('organizations.status', 'approved')
            ->wherePivot('is_active', true)
            ->firstOrFail();
    }

    // Display all organization orders
    public function index()
    {
        $organization = $this->currentOrganization();

        $orders = $organization->orders()
            ->latest()
            ->paginate(10);

        return view('organization.orders.index', compact(
            'organization',
            'orders'
        ));
    }

    // Display individual order details
    public function show(Order $order)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $order->organization_id === $organization->id,
            404
        );

        $order->load('items');

        return view('organization.orders.show', compact(
            'organization',
            'order'
        ));
    }

    // Update order and payment status
    public function update(Request $request, Order $order)
    {
        $organization = $this->currentOrganization();

        abort_unless(
            $order->organization_id === $organization->id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'processing',
                    'shipped',
                    'delivered',
                    'cancelled'
                ])
            ],

            'payment_status' => [
                'required',
                Rule::in([
                    'unpaid',
                    'paid',
                    'failed',
                    'refunded'
                ])
            ],
        ]);

        $order->update($validated);

        return redirect()
            ->route('organization.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }
}
