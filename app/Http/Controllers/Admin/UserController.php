<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(Request $request)
{
    $query = User::with('tenant');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    $users = $query->latest()->paginate(20);
    return view('admin.users.index', compact('users'));
}

    public function edit(User $user)
    {
        $tenants = Tenant::all();
        return view('admin.users.edit', compact('user', 'tenants'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tenant_id' => 'required|exists:tenants,id',
            'is_admin' => 'nullable|boolean',
        ]);

        $validated['is_admin'] = $request->has('is_admin');
        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}