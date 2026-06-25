@extends('layouts.app')

@section('title', 'Manage Stages')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pipeline Stages</h1>
            <p class="text-slate-500 text-sm">Manage your sales pipeline stages.</p>
        </div>
        <a href="{{ route('pipeline.stages.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> Add Stage
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Leads</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($stages as $stage)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-600">{{ $stage->order }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $stage->name }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-4 h-4 rounded-full" style="background:{{ $stage->color }}"></span>
                                    <span class="text-slate-600 text-xs">{{ $stage->color }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $stage->leads_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- SHOW BUTTON -->
                                    <a href="{{ route('pipeline.stages.show', $stage) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-sm" 
                                       title="View Stage">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <!-- EDIT BUTTON -->
                                    <a href="{{ route('pipeline.stages.edit', $stage) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 font-medium text-sm" 
                                       title="Edit Stage">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- DELETE BUTTON -->
                                    <form action="{{ route('pipeline.stages.destroy', $stage) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Delete this stage? This will set leads to unassigned.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 font-medium text-sm bg-transparent border-0 cursor-pointer" 
                                                title="Delete Stage">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection