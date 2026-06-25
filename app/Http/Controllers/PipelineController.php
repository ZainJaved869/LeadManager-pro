<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PipelineStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PipelineController extends Controller
{
    // ========== KANBAN BOARD ==========
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $stages = PipelineStage::where('tenant_id', $tenantId)
            ->orderBy('order')
            ->get();

        if ($stages->isEmpty()) {
            $this->createDefaultStages($tenantId);
            $stages = PipelineStage::where('tenant_id', $tenantId)
                ->orderBy('order')
                ->get();
        }

        foreach ($stages as $stage) {
            $stage->leads = Lead::where('tenant_id', $tenantId)
                ->where('stage_id', $stage->id)
                ->with(['assignedTo', 'createdBy'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $unassignedLeads = Lead::where('tenant_id', $tenantId)
            ->whereNull('stage_id')
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pipeline.index', compact('stages', 'unassignedLeads'));
    }

    public function updateStage(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'stage_id' => 'nullable|exists:pipeline_stages,id',
        ]);

        $lead = Lead::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($request->lead_id);

        // Store old stage for notification
        $oldStageId = $lead->stage_id;

        $lead->stage_id = $request->stage_id;

        if ($request->stage_id) {
            $stage = PipelineStage::find($request->stage_id);
            if ($stage && strtolower($stage->name) === 'won') {
                $lead->won_at = now();
            }
        }

        $lead->save();

        // --- NOTIFICATION: Stage changed ---
        if ($lead->stage_id && $lead->stage_id != $oldStageId && $lead->assigned_to) {
            $oldStageName = $oldStageId ? PipelineStage::find($oldStageId)?->name ?? 'None' : 'None';
            $newStageName = $lead->stage->name ?? 'None';

            if ($oldStageName !== $newStageName) {
                $this->createNotification(
                    $lead->assigned_to,
                    'Lead moved to ' . $newStageName,
                    'Lead "' . $lead->name . '" moved from "' . $oldStageName . '" to "' . $newStageName . '".',
                    route('leads.show', $lead)
                );
            }
        }

        return response()->json(['success' => true]);
    }

    public function reorderStages(Request $request)
    {
        $request->validate([
            'stages' => 'required|array',
            'stages.*' => 'exists:pipeline_stages,id',
        ]);

        foreach ($request->stages as $index => $stageId) {
            PipelineStage::where('tenant_id', Auth::user()->tenant_id)
                ->where('id', $stageId)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    // ========== STAGE MANAGEMENT (CRUD) ==========
    public function stagesIndex()
    {
        $stages = PipelineStage::where('tenant_id', Auth::user()->tenant_id)
            ->withCount('leads')
            ->orderBy('order')
            ->get();

        return view('pipeline.stages.index', compact('stages'));
    }

    public function stagesCreate()
    {
        $stages = PipelineStage::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('pipeline.stages.create', compact('stages'));
    }

    public function stagesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        PipelineStage::create($validated);

        return redirect()->route('pipeline.stages.index')->with('success', 'Stage created successfully.');
    }

    public function stagesEdit(PipelineStage $stage)
    {
        $this->authorizeTenant($stage);
        return view('pipeline.stages.edit', compact('stage'));
    }

    public function stagesUpdate(Request $request, PipelineStage $stage)
    {
        $this->authorizeTenant($stage);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
        ]);

        $stage->update($validated);

        return redirect()->route('pipeline.stages.index')->with('success', 'Stage updated successfully.');
    }

    // ========== SHOW STAGE ==========
    public function stagesShow(PipelineStage $stage)
    {
        $this->authorizeTenant($stage);
        $stage->load('leads.assignedTo');
        return view('pipeline.stages.show', compact('stage'));
    }

    public function stagesDestroy(PipelineStage $stage)
    {
        $this->authorizeTenant($stage);

        // Set leads with this stage to unassigned
        Lead::where('stage_id', $stage->id)->update(['stage_id' => null]);

        $stage->delete();

        return redirect()->route('pipeline.stages.index')->with('success', 'Stage deleted successfully.');
    }

    // ========== NOTIFICATION METHOD ==========
    protected function createNotification($userId, $title, $description, $url)
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\ActivityNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => json_encode([
                'title' => $title,
                'description' => $description,
                'url' => $url,
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ========== HELPER METHODS ==========
    private function createDefaultStages($tenantId)
    {
        $defaultStages = [
            ['name' => 'New Lead', 'color' => '#3B82F6', 'order' => 1],
            ['name' => 'Contacted', 'color' => '#8B5CF6', 'order' => 2],
            ['name' => 'Qualified', 'color' => '#EC4899', 'order' => 3],
            ['name' => 'Proposal Sent', 'color' => '#F59E0B', 'order' => 4],
            ['name' => 'Negotiation', 'color' => '#EF4444', 'order' => 5],
            ['name' => 'Won', 'color' => '#10B981', 'order' => 6],
            ['name' => 'Lost', 'color' => '#6B7280', 'order' => 7],
        ];

        foreach ($defaultStages as $stage) {
            PipelineStage::create([
                'tenant_id' => $tenantId,
                'name' => $stage['name'],
                'color' => $stage['color'],
                'order' => $stage['order'],
            ]);
        }
    }

    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}