@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">AI Assistant</h1>
            <p class="text-slate-500 text-sm">Generate sales content powered by Groq AI.</p>
        </div>
        <div>
            <span class="text-xs bg-slate-100 px-3 py-1 rounded-full text-slate-600">
                Provider: Groq
            </span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-slate-200/60 mb-6">
        <nav class="flex flex-wrap gap-2">
            <button class="tab-btn px-4 py-2 text-sm font-medium text-yellow-600 border-b-2 border-yellow-500" data-tab="email">Email Writer</button>
            <button class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700" data-tab="followup">Follow-up Generator</button>
            <button class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700" data-tab="proposal">Proposal Writer</button>
            <button class="tab-btn px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700" data-tab="summary">Lead Summary</button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="tab-email">
        @include('ai.partials.email', ['leads' => $leads])
    </div>
    <div class="tab-content hidden" id="tab-followup">
        @include('ai.partials.followup', ['leads' => $leads])
    </div>
    <div class="tab-content hidden" id="tab-proposal">
        @include('ai.partials.proposal', ['leads' => $leads])
    </div>
    <div class="tab-content hidden" id="tab-summary">
        @include('ai.partials.summary', ['leads' => $leads])
    </div>
</div>

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('text-yellow-600', 'border-yellow-500');
                b.classList.add('text-slate-500');
                b.style.borderBottom = 'none';
            });
            this.classList.add('text-yellow-600', 'border-yellow-500');
            this.style.borderBottom = '2px solid #f59e0b';

            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
            document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
        });
    });

    function copyToClipboard(elementId) {
        const content = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(content).then(() => {
            const btn = event.target;
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            setTimeout(() => btn.innerHTML = original, 2000);
        });
    }
</script>
@endpush
@endsection