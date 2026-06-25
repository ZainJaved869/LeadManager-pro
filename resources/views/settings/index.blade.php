@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Settings</h1>
        <span class="text-sm text-slate-500">Manage your company settings</span>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h3 class="text-slate-700 font-semibold">Company Settings</h3>
            <p class="text-sm text-slate-500">Update your company information and preferences.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-slate-700">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $tenant->name) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Company Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $tenant->email) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                        <textarea name="address" id="address" rows="3" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">{{ old('address', $tenant->address ?? '') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-slate-700">Timezone</label>
                            <select name="timezone" id="timezone" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                @foreach(['UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles', 'Europe/London', 'Europe/Paris', 'Asia/Dubai', 'Asia/Kolkata', 'Asia/Singapore', 'Australia/Sydney'] as $tz)
                                    <option value="{{ $tz }}" {{ old('timezone', $tenant->config['timezone'] ?? 'UTC') == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="currency" class="block text-sm font-medium text-slate-700">Currency</label>
                            <select name="currency" id="currency" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                @foreach(['USD', 'EUR', 'GBP', 'INR', 'SGD', 'AUD', 'CAD'] as $curr)
                                    <option value="{{ $curr }}" {{ old('currency', $tenant->config['currency'] ?? 'USD') == $curr ? 'selected' : '' }}>{{ $curr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date_format" class="block text-sm font-medium text-slate-700">Date Format</label>
                            <select name="date_format" id="date_format" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                @foreach(['Y-m-d', 'm/d/Y', 'd/m/Y', 'M d, Y'] as $fmt)
                                    <option value="{{ $fmt }}" {{ old('date_format', $tenant->config['date_format'] ?? 'Y-m-d') == $fmt ? 'selected' : '' }}>{{ $fmt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-1"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection