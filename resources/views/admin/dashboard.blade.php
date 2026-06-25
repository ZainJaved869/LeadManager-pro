@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Tenants</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalTenants }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalUsers }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Active Subscriptions</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalSubscriptions }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Leads</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalLeads }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-slate-800">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5 mb-6">
        <h3 class="text-slate-700 font-semibold mb-4">Revenue Over Time (Last 12 Months)</h3>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Recent Tenants & Subscriptions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60">
                <h3 class="text-slate-700 font-semibold">Recent Tenants</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-slate-500 font-medium">Name</th>
                            <th class="px-6 py-3 text-left text-slate-500 font-medium">Email</th>
                            <th class="px-6 py-3 text-left text-slate-500 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTenants as $tenant)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-800">{{ $tenant->name }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $tenant->email }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $tenant->status == 'active' ? 'bg-emerald-100 text-emerald-700' : ($tenant->status == 'trial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200/60">
                <h3 class="text-slate-700 font-semibold">Recent Subscriptions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-slate-500 font-medium">Tenant</th>
                            <th class="px-6 py-3 text-left text-slate-500 font-medium">Plan</th>
                            <th class="px-6 py-3 text-left text-slate-500 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSubscriptions as $sub)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-800">{{ $sub->tenant->name ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $sub->plan->name ?? 'N/A' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sub->status == 'active' ? 'bg-emerald-100 text-emerald-700' : ($sub->status == 'trial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const data = @json($revenueByMonth);
        const labels = data.map(item => item.month);
        const values = data.map(item => item.total);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: values,
                    backgroundColor: 'rgba(245, 158, 11, 0.6)',
                    borderColor: '#F59E0B',
                    borderWidth: 2,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#94a3b8' } }
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
    });
</script>
@endpush
@endsection