<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
   public function index(Request $request)
{
    $query = Subscription::with(['tenant', 'plan']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('tenant', function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        })->orWhereHas('plan', function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%");
        });
    }

    $subscriptions = $query->latest()->paginate(20);
    return view('admin.subscriptions.index', compact('subscriptions'));
}

    public function edit(Subscription $subscription)
    {
        $plans = Plan::all();
        return view('admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,cancelled,expired,trial',
            'trial_ends_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions')->with('success', 'Subscription updated.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions')->with('success', 'Subscription deleted.');
    }
}