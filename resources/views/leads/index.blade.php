@extends('layouts.app')

@section('title', 'Leads')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Leads</h1>
            <p class="text-slate-500 text-sm">Manage all your leads in one place.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-sm transition">
                <i class="fas fa-plus mr-2"></i> New Lead
            </a>
            <a href="{{ route('leads.export') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-file-export mr-2"></i> Export
            </a>
            <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data" class="inline">
                @csrf
                <input type="file" name="file" accept=".xlsx,.csv" class="hidden" id="importFile" onchange="this.form.submit()">
                <label for="importFile" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition cursor-pointer">
                    <i class="fas fa-file-import mr-2"></i> Import
                </label>
            </form>
        </div>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <form method="GET" action="{{ route('leads.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" placeholder="Search by name, email, company, or phone..." value="{{ request('search') }}"
                   class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500 w-64">
            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('leads.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Leads Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Stage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $lead->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $lead->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $lead->company ?? '-' }}</td>
                            <td class="px-6 py-4">
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
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">${{ number_format($lead->value ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $lead->assignedTo->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('leads.edit', $lead) }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm bg-transparent border-0 cursor-pointer" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-users text-3xl block mb-3 text-slate-300"></i>
                                No leads found. 
                                <a href="{{ route('leads.create') }}" class="text-yellow-600 hover:underline font-medium">Create your first lead</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $leads->links() }}
    </div>
</div>
@endsection