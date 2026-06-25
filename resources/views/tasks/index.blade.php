@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tasks</h1>
            <p class="text-slate-500 text-sm">Manage your tasks and to-dos.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> New Task
        </a>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" placeholder="Search by title or description..." value="{{ request('search') }}"
                   class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500 w-64">
            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Pending</p>
            <p class="text-2xl font-bold text-slate-800">{{ $pendingCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">In Progress</p>
            <p class="text-2xl font-bold text-slate-800">{{ $inProgressCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Completed</p>
            <p class="text-2xl font-bold text-slate-800">{{ $completedCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Overdue</p>
            <p class="text-2xl font-bold text-rose-600">{{ $overdueCount ?? 0 }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Tasks Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Related</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $task->title }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($task->due_date)
                                    <span class="{{ $task->due_date < now() && $task->status != 'completed' ? 'text-rose-600 font-medium' : '' }}">
                                        {{ $task->due_date->format('M d, Y') }}
                                        @if($task->due_date < now() && $task->status != 'completed')
                                            <i class="fas fa-exclamation-triangle text-rose-500 ml-1" title="Overdue"></i>
                                        @endif
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $task->priority_color }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $task->status_color }}">
                                    {{ $task->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
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
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-800" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent border-0 cursor-pointer"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-tasks text-3xl block mb-3 text-slate-300"></i>
                                No tasks found. <a href="{{ route('tasks.create') }}" class="text-yellow-600 hover:underline font-medium">Create your first task</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $tasks->links() }}</div>
</div>
@endsection