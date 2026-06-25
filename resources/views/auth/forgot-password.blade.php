@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('auth-title', 'Reset Your Password')

@section('auth-content')
@if (session('status'))
    <div class="bg-green-500/20 border border-green-400 text-green-200 px-4 py-2 rounded-lg mb-4">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
    <button type="submit" class="btn-gold w-full py-3 rounded-lg font-semibold text-white shadow-lg">
        Send Reset Link
    </button>
    <p class="text-center">
        <a href="{{ route('login') }}" class="auth-link text-sm">Back to Sign In</a>
    </p>
</form>
@endsection