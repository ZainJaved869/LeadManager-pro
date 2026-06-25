@extends('admin.layouts.app')

@section('title', 'Revenue Reports')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Revenue Reports</h1>
            <p class="text-slate-500 text-sm">Track revenue across all tenants.</p>
        </div>
        <div>
            <span class="text-sm text-slate-500">Total Revenue: ${{ number_format($totalRevenue, 2) }}</span>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-sm text-slate-500">Total Revenue</p>
                <p class="text-3xl font-bold text-slate-800">${{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-500">Paid Invoices</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $revenueByTenant->sum('total') > 0 ? number_format($revenueByTenant->sum('total'), 0) : '0' }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-500">Avg per Tenant</p>
                <p class="text-3xl font-bold text-indigo-600">
                    ${{ number_format($revenueByTenant->count() > 0 ? $totalRevenue / $revenueByTenant->count() : 0, 2) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-5 mb-6">
        <h3 class="text-slate-700 font-semibold mb-4">Monthly Revenue</h3>
        <div class="h-72">
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue by Tenant Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200/60">
            <h3 class="text-slate-700 font-semibold">Revenue by Tenant</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Tenant</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Email</th>
                        <th class="px-6 py-3 text-right text-slate-500 font-medium">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenueByTenant as $item)
                        <tr class="border-t border-slate-100 hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $item->tenant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $item->tenant->email ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right font-medium text-slate-700">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-file-invoice-dollar text-3xl block mb-3 text-slate-300"></i>
                                No revenue data found.
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
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        const data = @json($monthlyRevenue);
        const labels = data.map(item => item.month);
        const values = data.map(item => item.total);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: values,
                    backgroundColor: 'rgba(245, 158, 11, 0.7)',
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