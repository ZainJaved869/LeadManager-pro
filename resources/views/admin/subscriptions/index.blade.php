@extends('admin.layouts.app')

@section('title', 'Subscriptions')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manage Subscriptions</h1>
            <p class="text-slate-500 text-sm">All subscriptions across all tenants.</p>
        </div>
        <div>
            <span class="text-sm text-slate-500">Total: {{ $subscriptions->total() }}</span>
        </div>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <form method="GET" action="{{ route('admin.subscriptions') }}" class="flex items-center gap-2">
            <input type="text" name="search" placeholder="Search by tenant, plan, or status..." value="{{ request('search') }}"
                   class="rounded-lg border-slate-300 focus:border-yellow-500 focus:ring-yellow-500 w-64">
            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition shadow-sm">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.subscriptions') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition">
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

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200/60">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Trial Ends</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Ends At</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-600">{{ $subscription->id }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $subscription->tenant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $subscription->plan->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $subscription->status == 'active' ? 'bg-emerald-100 text-emerald-700' :
                                    ($subscription->status == 'trial' ? 'bg-yellow-100 text-yellow-700' :
                                    ($subscription->status == 'cancelled' ? 'bg-red-100 text-red-700' :
                                    'bg-slate-100 text-slate-500')) }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Delete this subscription?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent border-0 cursor-pointer" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-crown text-3xl block mb-3 text-slate-300"></i>
                                No subscriptions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection