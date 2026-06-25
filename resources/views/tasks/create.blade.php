@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h1 class="text-xl font-semibold text-slate-800">Create New Task</h1>
            <p class="text-sm text-slate-500">Add a new task or to-do item.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-slate-700">Assign To</label>
                            <select name="assigned_to" id="assigned_to" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="priority" class="block text-sm font-medium text-slate-700">Priority</label>
                            <select name="priority" id="priority" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                            <select name="status" id="status" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="reminder_at" class="block text-sm font-medium text-slate-700">Reminder</label>
                        <input type="datetime-local" name="reminder_at" id="reminder_at" value="{{ old('reminder_at') }}" class="mt-1 w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Related To</label>
                        <div class="grid grid-cols-2 gap-3 mt-1">
                            <div>
                                <select name="taskable_type" id="taskable_type" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                    <option value="">None</option>
                                    <option value="lead" {{ old('taskable_type') == 'lead' ? 'selected' : '' }}>Lead</option>
                                    <option value="company" {{ old('taskable_type') == 'company' ? 'selected' : '' }}>Company</option>
                                </select>
                            </div>
                            <div>
                                <select name="taskable_id" id="taskable_id" class="w-full rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500">
                                    <option value="">Select...</option>
                                    @foreach($leads as $lead)
                                        <option value="{{ $lead->id }}" data-type="lead" {{ old('taskable_type') == 'lead' && old('taskable_id') == $lead->id ? 'selected' : '' }}>{{ $lead->name }}</option>
                                    @endforeach
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" data-type="company" {{ old('taskable_type') == 'company' && old('taskable_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                        <i class="fas fa-save mr-1"></i> Save Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection