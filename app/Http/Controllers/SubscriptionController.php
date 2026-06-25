<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $currentSubscription = Auth::user()->tenant->subscription()->latest()->first();

        return view('subscription.plans', compact('plans', 'currentSubscription'));
    }

    public function checkout(Request $request, Plan $plan)
    {
        $tenant = Auth::user()->tenant;

        // Check if tenant already has a subscription
        $currentSubscription = $tenant->subscription()->latest()->first();

        return view('subscription.checkout', compact('plan', 'currentSubscription', 'tenant'));
    }

    public function subscribe(Request $request, Plan $plan)
    {
        $tenant = Auth::user()->tenant;

        // Cancel existing subscription if any
        $oldSubscription = $tenant->subscription()->latest()->first();
        if ($oldSubscription) {
            $oldSubscription->update(['status' => 'cancelled', 'cancelled_at' => now()]);
        }

        // Create new subscription
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => $plan->price > 0 ? 'trial' : 'active',
            'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
            'ends_at' => null,
        ]);

        // Create invoice (for record)
        Invoice::create([
            'tenant_id' => $tenant->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'status' => 'pending',
            'due_date' => now()->addDays(7),
            'items' => [
                ['name' => $plan->name . ' Plan', 'price' => $plan->price, 'interval' => $plan->interval],
            ],
            'notes' => 'Subscription started',
        ]);

        return redirect()->route('subscription.plans')->with('success', 'Subscription activated!');
    }

    public function invoices()
    {
        $invoices = Invoice::where('tenant_id', Auth::user()->tenant_id)
            ->with('subscription.plan')
            ->latest()
            ->paginate(20);

        return view('subscription.invoices', compact('invoices'));
    }

    public function cancel(Request $request)
    {
        $subscription = Auth::user()->tenant->subscription()->latest()->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'ends_at' => now()->addDays(30), // grace period
            ]);

            return redirect()->back()->with('success', 'Subscription cancelled. You will have access until ' . $subscription->ends_at->format('M d, Y'));
        }

        return redirect()->back()->with('error', 'No active subscription found.');
    }

    public function resume(Request $request)
    {
        $subscription = Auth::user()->tenant->subscription()->latest()->first();

        if ($subscription && $subscription->status === 'cancelled') {
            $subscription->update([
                'status' => 'active',
                'cancelled_at' => null,
                'ends_at' => null,
            ]);

            return redirect()->back()->with('success', 'Subscription resumed.');
        }

        return redirect()->back()->with('error', 'Unable to resume subscription.');
    }
}