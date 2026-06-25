@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>
    <!-- Welcome Message -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-slate-500">Here's what's happening with your sales pipeline today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Leads</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalLeads ?? 0 }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-emerald-600">
                <span class="font-medium">+{{ $newLeads ?? 0 }}</span> new this week
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Conversion Rate</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $conversionRate ?? 0 }}%</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-percent"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-emerald-600">
                <span class="font-medium">{{ $conversionRate ?? 0 }}%</span> won from active leads
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Revenue</p>
                    <p class="text-2xl font-bold text-slate-800">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-emerald-600">
                <span class="font-medium">${{ number_format($revenueChange ?? 0, 2) }}</span> from last month
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Open Tasks</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalTasks ?? 0 }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-rose-600">
                <span class="font-medium">{{ $overdueTasks ?? 0 }}</span> overdue tasks
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <h3 class="text-slate-700 font-semibold mb-4">Leads by Pipeline Stage</h3>
            <div class="h-64">
                <canvas id="leadsByStageChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <h3 class="text-slate-700 font-semibold mb-4">Revenue (Last 30 Days)</h3>
            <div class="h-64">
                <canvas id="salesOverTimeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Leads Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60 flex justify-between items-center">
            <h3 class="text-slate-700 font-semibold">Recent Leads</h3>
            <a href="{{ route('leads.index') }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Name</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Email</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Stage</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Value</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Assigned To</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLeads ?? [] as $lead)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $lead->name }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $lead->email ?? '-' }}</td>
                            <td class="px-6 py-3">
                                @if($lead->stage)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium text-white"
                                          style="background:{{ $lead->stage->color ?? '#4F46E5' }};">
                                        {{ $lead->stage->name }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-600">
                                        No Stage
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-medium text-slate-700">${{ number_format($lead->value ?? 0, 2) }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $lead->assignedTo->name ?? 'Unassigned' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                No leads found. 
                                <a href="{{ route('leads.create') }}" class="text-yellow-600 hover:underline">Create your first lead</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Leads by Stage Chart
    const ctx1 = document.getElementById('leadsByStageChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($stageLabels),
            datasets: [{
                label: 'Leads',
                data: @json($leadsByStage),
                backgroundColor: @json($stageColors),
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#94a3b8' }
                },
                x: {
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });

    // Revenue Chart
    const ctx2 = document.getElementById('salesOverTimeChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: @json($salesDates),
            datasets: [{
                label: 'Revenue ($)',
                data: @json($salesAmounts),
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#F59E0B'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#94a3b8' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#94a3b8' }
                },
                x: {
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endpush
@endsection