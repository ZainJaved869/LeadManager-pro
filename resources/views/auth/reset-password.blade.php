@extends('layouts.auth')

@section('title', 'Reset Password')
@section('auth-title', 'Set New Password')

@section('auth-content')
<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="token" value="{{ $request->token }}">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-200">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required readonly
               class="input-premium w-full rounded-lg px-4 py-3 text-white bg-white/5 cursor-not-allowed">
        @error('email')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-200">New Password</label>
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
        Reset Password
    </button>
</form>
@endsection