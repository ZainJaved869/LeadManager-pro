@extends('layouts.app')

@section('title', 'Lead Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $lead->name }}</h1>
            <p class="text-slate-500 text-sm">Lead details and activity.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Lead Information -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h3 class="text-slate-700 font-semibold">Lead Information</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-sm text-slate-500 font-medium">Email</span>
                <p class="text-slate-800">{{ $lead->email ?? '-' }}</p>
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Phone</span>
                <p class="text-slate-800">{{ $lead->phone ?? '-' }}</p>
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Company</span>
                <p class="text-slate-800">{{ $lead->company ?? '-' }}</p>
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Source</span>
                <p class="text-slate-800">{{ $lead->source ?? '-' }}</p>
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Stage</span>
                @if($lead->stage)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white"
                          style="background:{{ $lead->stage->color ?? '#4F46E5' }};">
                        {{ $lead->stage->name }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                        None
                    </span>
                @endif
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Value</span>
                <p class="text-slate-800 font-semibold">${{ number_format($lead->value ?? 0, 2) }}</p>
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Assigned To</span>
                <p class="text-slate-800">{{ $lead->assignedTo->name ?? 'Unassigned' }}</p>
            </div>
            <div>
                <span class="text-sm text-slate-500 font-medium">Created At</span>
                <p class="text-slate-800">{{ $lead->created_at->format('M d, Y') }}</p>
            </div>
            @if($lead->lost_reason)
                <div class="col-span-2">
                    <span class="text-sm text-slate-500 font-medium">Lost Reason</span>
                    <p class="text-slate-800">{{ $lead->lost_reason }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h3 class="text-slate-700 font-semibold">
                <i class="fas fa-sticky-note mr-2 text-yellow-500"></i> Notes
            </h3>
        </div>
        <div class="p-6">
            <!-- Add Note Form -->
            <form method="POST" action="{{ route('leads.notes.store', $lead) }}" class="flex flex-col sm:flex-row gap-3 mb-6">
                @csrf
                <input type="text" name="note" placeholder="Add a note..." required
                       class="flex-1 rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> Add Note
                </button>
            </form>

            <!-- Notes List -->
            @foreach($lead->notes->sortByDesc('created_at') as $note)
                <div class="border-b border-slate-100 last:border-0 py-3">
                    <p class="text-slate-700">{{ $note->note }}</p>
                    <div class="flex items-center gap-3 mt-1 text-xs text-slate-400">
                        <span>{{ $note->user->name }}</span>
                        <span>·</span>
                        <span>{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
            @if($lead->notes->isEmpty())
                <p class="text-slate-400 text-sm text-center py-4">No notes yet. Add one above.</p>
            @endif
        </div>
    </div>

    <!-- Attachments Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h3 class="text-slate-700 font-semibold">
                <i class="fas fa-paperclip mr-2 text-yellow-500"></i> Attachments
            </h3>
        </div>
        <div class="p-6">
            <!-- Upload Form -->
            <form method="POST" action="{{ route('leads.files.store', $lead) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 mb-6">
                @csrf
                <div class="flex-1">
                    <input type="file" name="file" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                </div>
                <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm whitespace-nowrap">
                    <i class="fas fa-upload mr-1"></i> Upload
                </button>
            </form>

            <!-- Files List -->
            @foreach($lead->files as $file)
                <div class="flex flex-wrap items-center justify-between border-b border-slate-100 last:border-0 py-3">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file text-slate-400"></i>
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium">
                            {{ $file->original_name }}
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-slate-400">{{ number_format($file->size / 1024, 1) }} KB</span>
                        <form method="POST" action="{{ route('leads.files.destroy', $file) }}" onsubmit="return confirm('Delete this file?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm bg-transparent border-0 cursor-pointer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            @if($lead->files->isEmpty())
                <p class="text-slate-400 text-sm text-center py-4">No attachments uploaded yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection