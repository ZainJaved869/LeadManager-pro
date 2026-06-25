@extends('admin.layouts.app')

@section('title', 'AI Settings')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">AI Settings</h1>
            <p class="text-sm text-slate-500">Configure AI provider and API keys.</p>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.ai-settings.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="provider" class="block text-sm font-medium text-slate-700">AI Provider <span class="text-red-500">*</span></label>
                        <select name="provider" id="provider" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="groq" {{ old('provider', $aiProvider) == 'groq' ? 'selected' : '' }}>Groq</option>
                            <option value="openai" {{ old('provider', $aiProvider) == 'openai' ? 'selected' : '' }}>OpenAI</option>
                        </select>
                        @error('provider') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div id="groq-key-input">
                        <label for="groq_key" class="block text-sm font-medium text-slate-700">Groq API Key</label>
                        <div class="relative mt-1">
                            <input type="password" name="groq_key" id="groq_key" value="{{ old('groq_key', $groqKey ?? '') }}" placeholder="Enter your Groq API key" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            <button type="button" onclick="togglePassword('groq_key')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-slate-500 hover:text-slate-700">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Leave blank to keep current key.</p>
                        @error('groq_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div id="openai-key-input" class="{{ old('provider', $aiProvider) == 'openai' ? '' : 'hidden' }}">
                        <label for="openai_key" class="block text-sm font-medium text-slate-700">OpenAI API Key</label>
                        <div class="relative mt-1">
                            <input type="password" name="openai_key" id="openai_key" value="{{ old('openai_key', '') }}" placeholder="Enter your OpenAI API key" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                            <button type="button" onclick="togglePassword('openai_key')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-slate-500 hover:text-slate-700">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Leave blank to keep current key.</p>
                        @error('openai_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                            <i class="fas fa-save mr-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                <h4 class="text-sm font-semibold text-slate-700">Current Configuration</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 text-sm">
                    <div><span class="text-slate-500">Provider:</span> <span class="font-medium">{{ ucfirst($aiProvider) }}</span></div>
                    <div><span class="text-slate-500">Groq Key:</span> <span class="font-medium">{{ $groqKey ? '••••••••' : 'Not set' }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }

    // Show/hide OpenAI key field based on provider selection
    document.getElementById('provider').addEventListener('change', function() {
        const openaiInput = document.getElementById('openai-key-input');
        if (this.value === 'openai') {
            openaiInput.classList.remove('hidden');
        } else {
            openaiInput.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection