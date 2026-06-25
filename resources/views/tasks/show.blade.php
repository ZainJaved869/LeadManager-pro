@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $task->title }}</h1>
            <p class="text-slate-500 text-sm">Task details and status.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-slate-500 font-medium">Title</span>
                    <p class="text-slate-800 font-semibold">{{ $task->title }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Status</span>
                    <span class="ml-2 px-3 py-1 rounded-full text-xs font-medium {{ $task->status_color }}">
                        {{ $task->status_label }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Priority</span>
                    <span class="ml-2 px-3 py-1 rounded-full text-xs font-medium {{ $task->priority_color }}">
                        {{ $task->priority_label }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Assigned To</span>
                    <p class="text-slate-800">{{ $task->assignedTo->name ?? 'Unassigned' }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Due Date</span>
                    <p class="text-slate-800">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Reminder</span>
                    <p class="text-slate-800">{{ $task->reminder_at ? $task->reminder_at->format('M d, Y H:i') : 'No reminder' }}</p>
                </div>
                <div class="col-span-2">
                    <span class="text-sm text-slate-500 font-medium">Related To</span>
                    <p class="text-slate-800">
                        @if($task->taskable)
                            @if($task->taskable_type == 'App\Models\Lead')
                                <a href="{{ route('leads.show', $task->taskable) }}" class="text-indigo-600 hover:underline">
                                    <i class="fas fa-users mr-1"></i> {{ $task->taskable->name ?? '' }}
                                </a>
                            @elseif($task->taskable_type == 'App\Models\Company')
                                <a href="{{ route('companies.show', $task->taskable) }}" class="text-indigo-600 hover:underline">
                                    <i class="fas fa-building mr-1"></i> {{ $task->taskable->name ?? '' }}
                                </a>
                            @else
                                {{ $task->taskable->name ?? '' }}
                            @endif
                        @else
                            Not linked
                        @endif
                    </p>
                </div>
                <div class="col-span-2">
                    <span class="text-sm text-slate-500 font-medium">Description</span>
                    <p class="text-slate-800 whitespace-pre-wrap">{{ $task->description ?? 'No description' }}</p>
                </div>
                <div class="col-span-2">
                    <span class="text-sm text-slate-500 font-medium">Created</span>
                    <p class="text-slate-800">{{ $task->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="border-t border-slate-200/60 pt-4 flex flex-wrap gap-3">
                @if($task->status != 'completed')
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition shadow-sm">
                            <i class="fas fa-check mr-1"></i> Mark Complete
                        </button>
                    </form>
                @endif
                @if($task->status != 'in_progress' && $task->status != 'completed')
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-lg transition shadow-sm">
                            <i class="fas fa-play mr-1"></i> Start Progress
                        </button>
                    </form>
                @endif
                @if($task->status != 'cancelled' && $task->status != 'completed')
                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-medium rounded-lg transition shadow-sm">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </form>
                @endif
            </div>

            <!-- ===== REMINDER SECTION ===== -->
            <div class="mt-6 border-t border-slate-200/60 pt-4">
                <h4 class="font-semibold text-slate-700 mb-3">Set Reminder</h4>
                <form method="POST" action="{{ route('tasks.reminder', $task) }}" class="flex flex-wrap gap-3">
                    @csrf
                    <input type="datetime-local" name="remind_at" required class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <select name="type" class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="email">Email</option>
                        <option value="in_app">In-App</option>
                        <option value="both">Both</option>
                    </select>
                    <input type="text" name="description" placeholder="Reminder note (optional)" class="flex-1 rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition shadow-sm">
                        <i class="fas fa-bell mr-1"></i> Set Reminder
                    </button>
                </form>
            </div>
            <!-- ===== END REMINDER SECTION ===== -->

        </div>
    </div>
</div>
@endsection