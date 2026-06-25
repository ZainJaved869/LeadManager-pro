@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Analytics Dashboard</h1>
            <p class="text-slate-500 text-sm">Real-time insights into your sales performance.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
                <i class="fas fa-print mr-2"></i> Export Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Total Leads</p>
            <p class="text-2xl font-bold text-slate-800">{{ $totalLeads }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Won</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $totalWon }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Lost</p>
            <p class="text-2xl font-bold text-rose-600">{{ $totalLost }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 text-center">
            <p class="text-sm text-slate-500">Conversion Rate</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $conversionRate }}%</p>
        </div>
    </div>

    <!-- Revenue & Conversion -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5">
            <h3 class="text-slate-700 font-semibold mb-4">Revenue Over Time</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5">
            <h3 class="text-slate-700 font-semibold mb-4">Leads by Stage (Funnel)</h3>
            <div class="h-64">
                <canvas id="funnelChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Leads by Stage & Team Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5">
            <h3 class="text-slate-700 font-semibold mb-4">Leads by Stage</h3>
            <div class="h-64">
                <canvas id="stageChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5">
            <h3 class="text-slate-700 font-semibold mb-4">Team Performance</h3>
            <div class="h-64">
                <canvas id="teamChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Task Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5">
        <h3 class="text-slate-700 font-semibold mb-4">Task Summary</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-3 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-500">Pending</p>
                <p class="text-xl font-bold text-slate-800">{{ $pendingTasks }}</p>
            </div>
            <div class="text-center p-3 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-500">Completed</p>
                <p class="text-xl font-bold text-emerald-600">{{ $completedTasks }}</p>
            </div>
            <div class="text-center p-3 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-500">Total Revenue</p>
                <p class="text-xl font-bold text-slate-800">${{ number_format($totalValue, 2) }}</p>
            </div>
            <div class="text-center p-3 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-500">Avg Deal Size</p>
                <p class="text-xl font-bold text-slate-800">${{ $totalWon > 0 ? number_format($totalValue / $totalWon, 2) : '0.00' }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Revenue Chart
        const ctx1 = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: @json($revenueLabels),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($revenueData),
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
                    legend: { labels: { color: '#94a3b8' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#94a3b8' } },
                    x: { ticks: { color: '#94a3b8' } }
                }
            }
        });

        // 2. Funnel Chart
        const ctx2 = document.getElementById('funnelChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: @json($funnelLabels),
                datasets: [{
                    label: 'Leads',
                    data: @json($funnelData),
                    backgroundColor: @json($stageColors),
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { color: '#94a3b8' } },
                    y: { ticks: { color: '#94a3b8' } }
                }
            }
        });

        // 3. Stage Distribution Chart (Pie)
        const ctx3 = document.getElementById('stageChart').getContext('2d');
        new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: @json($stageLabels),
                datasets: [{
                    data: @json($stageData),
                    backgroundColor: @json($stageColors),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', padding: 10 }
                    }
                }
            }
        });

        // 4. Team Performance Chart
        const ctx4 = document.getElementById('teamChart').getContext('2d');
        new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: @json($teamNames),
                datasets: [
                    {
                        label: 'Assigned',
                        data: @json($teamLeads),
                        backgroundColor: '#4F46E5',
                    },
                    {
                        label: 'Won',
                        data: @json($teamWon),
                        backgroundColor: '#10B981',
                    },
                    {
                        label: 'Lost',
                        data: @json($teamLost),
                        backgroundColor: '#EF4444',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: '#94a3b8' }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#94a3b8' } },
                    x: { ticks: { color: '#94a3b8' } }
                }
            }
        });
    });
</script>
@endpush
@endsection