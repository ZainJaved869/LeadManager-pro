@extends('layouts.app')

@section('title', 'Edit Contact')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">Edit Contact</h1>
            <p class="text-sm text-slate-500">Update contact details for {{ $company->name }}.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('contacts.update', [$company, $contact]) }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-slate-700">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $contact->first_name) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-slate-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $contact->last_name) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $contact->email) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $contact->phone) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium text-slate-700">Position</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $contact->position) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary', $contact->is_primary) ? 'checked' : '' }} class="rounded border-slate-300 text-yellow-500 focus:ring-yellow-500">
                        <label for="is_primary" class="text-sm text-slate-700">Primary contact</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('companies.show', $company) }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update Contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection