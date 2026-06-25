@extends('layouts.app')

@section('title', 'Stage Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">{{ $stage->name }}</h1>
                <p class="text-sm text-slate-500">Pipeline stage details and lead summary.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('pipeline.stages.edit', $stage) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('pipeline.stages.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>
        <div class="p-6">
            <!-- Stage Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <span class="text-sm text-slate-500 font-medium">Name</span>
                    <p class="text-slate-800 font-semibold">{{ $stage->name }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Color</span>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-6 h-6 rounded-full" style="background:{{ $stage->color }}"></span>
                        <span class="text-slate-600">{{ $stage->color }}</span>
                    </div>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Display Order</span>
                    <p class="text-slate-800">{{ $stage->order }}</p>
                </div>
                <div>
                    <span class="text-sm text-slate-500 font-medium">Total Leads</span>
                    <p class="text-slate-800 font-semibold">{{ $stage->leads->count() }}</p>
                </div>
            </div>

            <!-- Leads in this stage -->
            <div>
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Leads in this Stage</h3>
                @if($stage->leads->count() > 0)
                    <div class="bg-slate-50 rounded-lg overflow-hidden border border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-slate-500 font-medium">Name</th>
                                    <th class="px-4 py-3 text-left text-slate-500 font-medium">Email</th>
                                    <th class="px-4 py-3 text-left text-slate-500 font-medium">Value</th>
                                    <th class="px-4 py-3 text-left text-slate-500 font-medium">Assigned To</th>
                                    <th class="px-4 py-3 text-right text-slate-500 font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stage->leads as $lead)
                                    <tr class="border-t border-slate-200 hover:bg-white transition">
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $lead->name }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $lead->email ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600">${{ number_format($lead->value ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $lead->assignedTo->name ?? 'Unassigned' }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-inbox text-3xl block mb-2"></i>
                        No leads in this stage.
                    </div>
                @endif
            </div>

            <!-- Quick action buttons -->
            <div class="mt-6 flex gap-3">
                <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add Lead
                </a>
                <a href="{{ route('pipeline.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                    <i class="fas fa-columns mr-2"></i> View Kanban
                </a>
            </div>
        </div>
    </div>
</div>
@endsection