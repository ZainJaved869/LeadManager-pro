@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">Confirm Subscription</h1>
            <p class="text-sm text-slate-500">Review your plan details before subscribing.</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">{{ $plan->name }} Plan</h3>
                    <p class="text-slate-500">{{ $plan->description }}</p>
                    <div class="mt-4">
                        <span class="text-3xl font-bold text-slate-800">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-sm text-slate-500">/{{ $plan->interval }}</span>
                    </div>
                    @if($plan->is_trial)
                        <p class="text-sm text-yellow-600 mt-2"><i class="fas fa-gift mr-1"></i> {{ $plan->trial_days }} days free trial</p>
                    @endif
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        @foreach($plan->features as $feature)
                            <li class="flex items-center gap-2"><i class="fas fa-check text-yellow-500"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold text-slate-700">Order Summary</h4>
                        <div class="flex justify-between mt-2 text-sm">
                            <span class="text-slate-500">{{ $plan->name }} Plan</span>
                            <span class="font-medium">${{ number_format($plan->price, 2) }}</span>
                        </div>
                        @if($plan->is_trial && $plan->price > 0)
                            <div class="flex justify-between text-sm text-emerald-600">
                                <span>Trial discount</span>
                                <span>-${{ number_format($plan->price, 2) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between font-bold">
                                <span>Today</span>
                                <span>$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm text-slate-500 mt-1">
                                <span>After {{ $plan->trial_days }} days</span>
                                <span>${{ number_format($plan->price, 2) }}/{{ $plan->interval }}</span>
                            </div>
                        @else
                            <hr class="my-2">
                            <div class="flex justify-between font-bold">
                                <span>Total</span>
                                <span>${{ number_format($plan->price, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    @if($currentSubscription && $currentSubscription->isActive())
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-sm text-yellow-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                You currently have a {{ $currentSubscription->plan->name }} plan. Subscribing will replace it.
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('subscription.subscribe', $plan) }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            @if($plan->price == 0)
                                Activate Free Plan
                            @elseif($plan->is_trial)
                                Start Free Trial
                            @else
                                Subscribe Now
                            @endif
                        </button>
                        <p class="text-xs text-slate-500 text-center mt-2">You will be redirected to payment gateway shortly.</p>
                    </form>

                    <a href="{{ route('subscription.plans') }}" class="block text-center mt-4 text-sm text-slate-500 hover:text-slate-700">← Back to plans</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection