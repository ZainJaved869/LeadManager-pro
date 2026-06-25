@extends('layouts.app')

@section('title', 'Edit Lead')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">Edit Lead</h1>
            <p class="text-sm text-slate-500">Update the lead details below.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('leads.update', $lead) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" required
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}"
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}"
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Company -->
                    <div>
                        <label for="company" class="block text-sm font-medium text-slate-700">Company</label>
                        <input type="text" name="company" id="company" value="{{ old('company', $lead->company) }}"
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Source -->
                    <div>
                        <label for="source" class="block text-sm font-medium text-slate-700">Source</label>
                        <input type="text" name="source" id="source" value="{{ old('source', $lead->source) }}"
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500"
                               placeholder="e.g. Website, Referral, LinkedIn">
                        @error('source') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Stage -->
                    <div>
                        <label for="stage_id" class="block text-sm font-medium text-slate-700">Stage</label>
                        <select name="stage_id" id="stage_id"
                                class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="">None</option>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', $lead->stage_id) == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('stage_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Value -->
                    <div>
                        <label for="value" class="block text-sm font-medium text-slate-700">Value ($)</label>
                        <input type="number" step="0.01" name="value" id="value" value="{{ old('value', $lead->value) }}"
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500"
                               placeholder="0.00">
                        @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-slate-700">Assigned To</label>
                        <select name="assigned_to" id="assigned_to"
                                class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contacted At -->
                    <div>
                        <label for="contacted_at" class="block text-sm font-medium text-slate-700">Contacted At</label>
                        <input type="date" name="contacted_at" id="contacted_at" value="{{ old('contacted_at', $lead->contacted_at ? $lead->contacted_at->format('Y-m-d') : '') }}"
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('contacted_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Lost Reason (full width) -->
                <div class="mt-4">
                    <label for="lost_reason" class="block text-sm font-medium text-slate-700">Lost Reason (if any)</label>
                    <textarea name="lost_reason" id="lost_reason" rows="3"
                              class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500"
                              placeholder="Why was this lead lost? (budget, competitor, etc.)">{{ old('lost_reason', $lead->lost_reason) }}</textarea>
                    @error('lost_reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Submit -->
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('leads.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection