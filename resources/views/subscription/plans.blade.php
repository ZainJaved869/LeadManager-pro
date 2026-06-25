@extends('layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Subscription Plans</h1>
            <p class="text-slate-500 text-sm">Choose the plan that fits your business.</p>
        </div>
        <a href="{{ route('subscription.invoices') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
            <i class="fas fa-file-invoice mr-2"></i> Invoices
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden hover:shadow-md transition">
                <div class="p-6">
                    @if($plan->slug === 'free')
                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Free</span>
                    @elseif($plan->is_trial)
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $plan->trial_days }} days trial</span>
                    @endif
                    <h3 class="text-xl font-bold text-slate-800 mt-2">{{ $plan->name }}</h3>
                    <p class="text-sm text-slate-500">{{ $plan->description }}</p>
                    <div class="mt-4">
                        <span class="text-3xl font-bold text-slate-800">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-sm text-slate-500">/{{ $plan->interval }}</span>
                    </div>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        @foreach($plan->features as $feature)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-yellow-500"></i> {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-6">
                        @if($currentSubscription && $currentSubscription->plan_id == $plan->id && $currentSubscription->isActive())
                            <span class="block w-full text-center px-4 py-2 bg-emerald-100 text-emerald-700 font-semibold rounded-lg">Current Plan</span>
                        @else
                            <a href="{{ route('subscription.checkout', $plan) }}" class="block w-full text-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                                @if($plan->price == 0)
                                    Get Started
                                @else
                                    Subscribe
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection