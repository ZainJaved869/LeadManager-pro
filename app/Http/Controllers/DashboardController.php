<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        // Stats
        $totalLeads = Lead::where('tenant_id', $tenantId)->count();
        $totalTasks = Task::where('tenant_id', $tenantId)->where('status', '!=', 'completed')->count();
        $overdueTasks = Task::where('tenant_id', $tenantId)
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->count();

        $wonStageId = PipelineStage::where('tenant_id', $tenantId)->where('name', 'Won')->value('id');
        $lostStageId = PipelineStage::where('tenant_id', $tenantId)->where('name', 'Lost')->value('id');

        $totalRevenue = Lead::where('tenant_id', $tenantId)
            ->where('stage_id', $wonStageId)
            ->sum('value');

        $totalActive = Lead::where('tenant_id', $tenantId)
            ->where('stage_id', '!=', $lostStageId)
            ->count();

        $totalWon = Lead::where('tenant_id', $tenantId)
            ->where('stage_id', $wonStageId)
            ->count();

        $conversionRate = $totalActive > 0 ? round(($totalWon / $totalActive) * 100, 1) : 0;

        // Leads by stage (for chart)
        $stages = PipelineStage::where('tenant_id', $tenantId)->orderBy('order')->get();
        $stageLabels = [];
        $leadsByStage = [];
        $stageColors = [];
        foreach ($stages as $stage) {
            $stageLabels[] = $stage->name;
            $stageColors[] = $stage->color ?? '#4F46E5';
            $leadsByStage[] = Lead::where('tenant_id', $tenantId)
                ->where('stage_id', $stage->id)
                ->count();
        }

        // If no stages exist, provide default dummy data to avoid empty charts
        if (empty($stageLabels)) {
            $stageLabels = ['New Lead', 'Contacted', 'Qualified', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'];
            $stageColors = ['#4F46E5', '#8B5CF6', '#EC4899', '#F59E0B', '#EF4444', '#10B981', '#6B7280'];
            $leadsByStage = [0, 0, 0, 0, 0, 0, 0];
        }

        // Revenue over time (last 30 days)
        $salesOverTime = Lead::where('tenant_id', $tenantId)
            ->where('stage_id', $wonStageId)
            ->where('won_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(won_at) as date'), DB::raw('SUM(value) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesDates = $salesOverTime->pluck('date')->toArray();
        $salesAmounts = $salesOverTime->pluck('total')->toArray();

        // If no sales data, provide empty arrays (Chart.js will handle)
        if (empty($salesDates)) {
            $salesDates = ['No Data'];
            $salesAmounts = [0];
        }

        // Recent leads
        $recentLeads = Lead::where('tenant_id', $tenantId)
            ->with(['stage', 'assignedTo'])
            ->latest()
            ->limit(5)
            ->get();

        // New leads this week
        $newLeads = Lead::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        // Revenue change (compare with last month)
        $lastMonthRevenue = Lead::where('tenant_id', $tenantId)
            ->where('stage_id', $wonStageId)
            ->whereBetween('won_at', [now()->subMonths(2), now()->subMonths(1)])
            ->sum('value');

        $revenueChange = $totalRevenue - $lastMonthRevenue;

        return view('dashboard', compact(
            'totalLeads', 'totalTasks', 'overdueTasks',
            'totalRevenue', 'conversionRate',
            'stageLabels', 'leadsByStage', 'stageColors',
            'salesDates', 'salesAmounts',
            'recentLeads', 'newLeads', 'revenueChange'
        ));
    }
}