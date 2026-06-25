@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">Edit Company</h1>
            <p class="text-sm text-slate-500">Update the company details.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('companies.update', $company) }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        </div>
                    </div>
                    <div>
                        <label for="website" class="block text-sm font-medium text-slate-700">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500" placeholder="https://example.com">
                    </div>
                    <div>
                        <label for="industry" class="block text-sm font-medium text-slate-700">Industry</label>
                        <input type="text" name="industry" id="industry" value="{{ old('industry', $company->industry) }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                        <textarea name="address" id="address" rows="3" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">{{ old('address', $company->address) }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection