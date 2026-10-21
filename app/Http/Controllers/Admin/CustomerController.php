<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    // Show all customers
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    // Show add customer form
    public function create()
    {
        return view('admin.customers.create');
    }

    // Save new customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = new User();
        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->password = Hash::make($validated['password']);
        $customer->role = 'customer';
        $customer->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer added successfully.');
    }

    // Show edit customer form
    public function edit(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        return view('admin.customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($customer->id),
            ],
        ]);

        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    // Soft delete customer
    public function destroy(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer moved to Trash.');
    }

    
    // Show deleted customers
    public function trash()
    {
        $customers = User::onlyTrashed()
            ->where('role', 'customer')
            ->latest('deleted_at')
            ->get();

        return view('admin.customers.trash', compact('customers'));
    }

    // Restore deleted customer
    public function restore($id)
    {
        $customer = User::onlyTrashed()
            ->where('role', 'customer')
            ->findOrFail($id);

        $customer->restore();

        return redirect()
            ->route('admin.customers.trash')
            ->with('success', 'Customer restored successfully.');
    }

}
