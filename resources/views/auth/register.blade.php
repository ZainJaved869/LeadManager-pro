@extends('layouts.auth')

@section('title', 'Register')
@section('auth-title', 'Start Your Free Trial')

@section('auth-content')
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label for="company_name" class="block text-sm font-medium text-gray-200">Company Name</label>
        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
               class="input-premium w-full rounded-lg px-4 py-3 placeholder-gray-400 text-white"
               placeholder="Acme Inc.">
        @error('company_name')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="name" class="block text-sm font-medium text-gray-200">Full Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
               class="input-premium w-full rounded-lg px-4 py-3 placeholder-gray-400 text-white"
               placeholder="John Doe">
        @error('name')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-200">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required
               class="input-premium w-full rounded-lg px-4 py-3 placeholder-gray-400 text-white"
               placeholder="you@example.com">
        @error('email')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
        <input type="password" id="password" name="password" required
               class="input-premium w-full rounded-lg px-4 py-3 placeholder-gray-400 text-white"
               placeholder="••••••••">
        @error('password')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-200">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required
               class="input-premium w-full rounded-lg px-4 py-3 placeholder-gray-400 text-white"
               placeholder="••••••••">
    </div>

    <button type="submit" class="btn-gold w-full py-3 rounded-lg font-semibold text-white shadow-lg">
        Create Account
    </button>

    <p class="text-center text-gray-300 text-sm">
        Already have an account?
        <a href="{{ route('login') }}" class="auth-link font-medium">Sign in</a>
    </p>
</form>
@endsection