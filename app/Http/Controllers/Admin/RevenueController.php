<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function index()
    {
        // Total revenue
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');

        // Revenue by tenant
        $revenueByTenant = Invoice::where('status', 'paid')
            ->select('tenant_id', DB::raw('SUM(amount) as total'))
            ->groupBy('tenant_id')
            ->with('tenant')
            ->orderBy('total', 'desc')
            ->get();

        // Monthly revenue (last 12 months)
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->select(DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'), DB::raw('SUM(amount) as total'))
            ->whereNotNull('paid_at')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->limit(12)
            ->get();

        return view('admin.revenue.index', compact(
            'totalRevenue', 'revenueByTenant', 'monthlyRevenue'
        ));
    }
}