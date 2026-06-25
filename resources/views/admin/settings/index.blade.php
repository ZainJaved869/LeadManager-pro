@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">System Settings</h1>
            <p class="text-sm text-slate-500">Manage application-wide settings.</p>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-slate-700">Application Name <span class="text-red-500">*</span></label>
                        <input type="text" name="app_name" id="app_name" value="{{ old('app_name', config('app.name')) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('app_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="app_url" class="block text-sm font-medium text-slate-700">Application URL <span class="text-red-500">*</span></label>
                        <input type="url" name="app_url" id="app_url" value="{{ old('app_url', config('app.url')) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('app_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-slate-700">Timezone <span class="text-red-500">*</span></label>
                        <select name="timezone" id="timezone" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @foreach(['UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles', 'Europe/London', 'Europe/Paris', 'Asia/Dubai', 'Asia/Kolkata', 'Asia/Singapore', 'Australia/Sydney'] as $tz)
                                <option value="{{ $tz }}" {{ old('timezone', config('app.timezone')) == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                        @error('timezone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-slate-700">Currency</label>
                        <select name="currency" id="currency" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @foreach(['USD', 'EUR', 'GBP', 'INR', 'SGD', 'AUD', 'CAD'] as $curr)
                                <option value="{{ $curr }}" {{ old('currency', config('app.currency', 'USD')) == $curr ? 'selected' : '' }}>{{ $curr }}</option>
                            @endforeach
                        </select>
                        @error('currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ old('maintenance_mode', config('app.maintenance_mode', false)) ? 'checked' : '' }} class="rounded border-slate-300 text-yellow-500 focus:ring-yellow-500">
                        <label for="maintenance_mode" class="text-sm text-slate-700 font-medium">Maintenance Mode</label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                            <i class="fas fa-save mr-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection