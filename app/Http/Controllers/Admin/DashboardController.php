<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $totalUsers = User::count();
        $totalSubscriptions = Subscription::where('status', 'active')->count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        $totalLeads = Lead::count();

        // Revenue by month (last 12 months)
        $revenueByMonth = Invoice::where('status', 'paid')
            ->select(DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'), DB::raw('SUM(amount) as total'))
            ->whereNotNull('paid_at')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->limit(12)
            ->get();

        // Recent tenants
        $recentTenants = Tenant::latest()->limit(5)->get();

        // Recent subscriptions
        $recentSubscriptions = Subscription::with(['tenant', 'plan'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTenants', 'totalUsers', 'totalSubscriptions',
            'totalRevenue', 'totalLeads', 'revenueByMonth',
            'recentTenants', 'recentSubscriptions'
        ));
    }
}