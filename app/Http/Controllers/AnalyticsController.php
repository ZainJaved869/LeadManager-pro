<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        // 1. Leads by Stage (Pie Chart & Bar Chart)
        $stages = PipelineStage::where('tenant_id', $tenantId)->orderBy('order')->get();
        $stageLabels = [];
        $stageData = [];
        $stageColors = [];
        foreach ($stages as $stage) {
            $stageLabels[] = $stage->name;
            $stageColors[] = $stage->color ?? '#4F46E5';
            $stageData[] = Lead::where('tenant_id', $tenantId)->where('stage_id', $stage->id)->count();
        }

        // 2. Revenue over Time (Last 12 months)
        $revenueMonthly = Lead::where('tenant_id', $tenantId)
            ->whereNotNull('won_at')
            ->select(DB::raw('YEAR(won_at) as year, MONTH(won_at) as month, SUM(value) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $revenueLabels = [];
        $revenueData = [];
        foreach ($revenueMonthly as $item) {
            $revenueLabels[] = date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            $revenueData[] = $item->total;
        }

        // If no revenue data, provide dummy
        if (empty($revenueLabels)) {
            $revenueLabels = ['No Data'];
            $revenueData = [0];
        }

        // 3. Conversion Funnel (counts by stage)
        $funnelLabels = [];
        $funnelData = [];
        foreach ($stages as $stage) {
            $funnelLabels[] = $stage->name;
            $funnelData[] = Lead::where('tenant_id', $tenantId)->where('stage_id', $stage->id)->count();
        }

        // 4. Team Performance (Leads assigned per user + won/lost)
        $teamData = User::where('tenant_id', $tenantId)
            ->withCount(['leadsAssigned' => function ($q) {
                $q->whereNull('deleted_at');
            }])
            ->withCount(['leadsAssigned as won_count' => function ($q) {
                $q->whereHas('stage', function ($sq) {
                    $sq->where('name', 'Won');
                });
            }])
            ->withCount(['leadsAssigned as lost_count' => function ($q) {
                $q->whereHas('stage', function ($sq) {
                    $sq->where('name', 'Lost');
                });
            }])
            ->get();

        $teamNames = [];
        $teamLeads = [];
        $teamWon = [];
        $teamLost = [];
        foreach ($teamData as $user) {
            $teamNames[] = $user->name;
            $teamLeads[] = $user->leads_assigned_count;
            $teamWon[] = $user->won_count;
            $teamLost[] = $user->lost_count;
        }

        // 5. Overall Stats
        $totalLeads = Lead::where('tenant_id', $tenantId)->count();
        $totalWon = Lead::where('tenant_id', $tenantId)->whereHas('stage', function ($q) {
            $q->where('name', 'Won');
        })->count();
        $totalLost = Lead::where('tenant_id', $tenantId)->whereHas('stage', function ($q) {
            $q->where('name', 'Lost');
        })->count();
        $totalValue = Lead::where('tenant_id', $tenantId)->whereHas('stage', function ($q) {
            $q->where('name', 'Won');
        })->sum('value');
        $pendingTasks = Task::where('tenant_id', $tenantId)->where('status', 'pending')->count();
        $completedTasks = Task::where('tenant_id', $tenantId)->where('status', 'completed')->count();

        // Conversion rate
        $conversionRate = $totalLeads > 0 ? round(($totalWon / $totalLeads) * 100, 1) : 0;

        return view('analytics.index', compact(
            'stageLabels', 'stageData', 'stageColors',
            'revenueLabels', 'revenueData',
            'funnelLabels', 'funnelData',
            'teamNames', 'teamLeads', 'teamWon', 'teamLost',
            'totalLeads', 'totalWon', 'totalLost', 'totalValue',
            'pendingTasks', 'completedTasks', 'conversionRate'
        ));
    }
}