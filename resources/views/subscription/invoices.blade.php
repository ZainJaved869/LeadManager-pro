@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div>
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Invoices</h1>
            <p class="text-slate-500 text-sm">View your payment history.</p>
        </div>
        <a href="{{ route('subscription.plans') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Plans
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Invoice #</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Plan</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Amount</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Status</th>
                        <th class="px-6 py-3 text-left text-slate-500 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-3 font-medium text-slate-800">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $invoice->subscription->plan->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 font-medium text-slate-700">${{ number_format($invoice->amount, 2) }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $invoice->status == 'paid' ? 'bg-emerald-100 text-emerald-700' : ($invoice->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $invoice->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-500">{{ $invoice->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-file-invoice text-3xl block mb-3 text-slate-300"></i>
                                No invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
</div>
@endsection