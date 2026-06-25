@extends('layouts.auth')

@section('title', 'Login')
@section('auth-title', 'Welcome Back')

@section('auth-content')
<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-200">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
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

    <div class="flex items-center justify-between">
        <label class="flex items-center text-sm text-gray-300">
            <input type="checkbox" name="remember" class="rounded border-gray-600 bg-transparent text-yellow-400 focus:ring-yellow-400">
            <span class="ml-2">Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="auth-link text-sm">Forgot password?</a>
    </div>

    <button type="submit" class="btn-gold w-full py-3 rounded-lg font-semibold text-white shadow-lg">
        Sign In
    </button>

    <p class="text-center text-gray-300 text-sm">
        Don't have an account?
        <a href="{{ route('register') }}" class="auth-link font-medium">Create one</a>
    </p>
</form>
@endsection