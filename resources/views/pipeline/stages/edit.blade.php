@extends('layouts.app')

@section('title', 'Edit Stage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">Edit Pipeline Stage</h1>
            <p class="text-sm text-slate-500">Update the stage details.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('pipeline.stages.update', $stage) }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Stage Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $stage->name) }}" required
                               class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="color" class="block text-sm font-medium text-slate-700">Color</label>
                        <input type="color" name="color" id="color" value="{{ old('color', $stage->color) }}"
                               class="mt-1 w-20 h-10 rounded border-slate-300 cursor-pointer">
                        @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="order" class="block text-sm font-medium text-slate-700">Display Order</label>
                        <input type="number" name="order" id="order" value="{{ old('order', $stage->order) }}"
                               class="mt-1 w-24 rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('pipeline.stages.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-1"></i> Update Stage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection