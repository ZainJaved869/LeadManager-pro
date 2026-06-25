<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIController extends Controller
{
    protected $ai;

    public function __construct(AIService $ai)
    {
        $this->ai = $ai;
    }

    public function index()
    {
        $leads = Lead::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('ai.index', compact('leads'));
    }

    public function generateEmail(Request $request)
    {
        $request->validate([
            'context' => 'required|string|min:10',
            'lead_id' => 'nullable|exists:leads,id',
        ]);

        $context = $request->context;

        if ($request->lead_id) {
            $lead = Lead::find($request->lead_id);
            $context .= "\n\nLead Details:\nName: " . $lead->name .
                        "\nCompany: " . ($lead->company ?? 'Not provided') .
                        "\nEmail: " . ($lead->email ?? 'Not provided') .
                        "\nStage: " . ($lead->stage->name ?? 'New') .
                        "\nValue: $" . ($lead->value ?? 0);
        }

        $result = $this->ai->generateEmail($context);

        return response()->json([
            'success' => true,
            'content' => $result,
        ]);
    }

    public function generateFollowup(Request $request)
    {
        $request->validate([
            'context' => 'required|string|min:10',
            'previous_email' => 'nullable|string',
            'lead_id' => 'nullable|exists:leads,id',
        ]);

        $context = $request->context;

        if ($request->lead_id) {
            $lead = Lead::find($request->lead_id);
            $context .= "\n\nLead Details:\nName: " . $lead->name .
                        "\nCompany: " . ($lead->company ?? 'Not provided') .
                        "\nStage: " . ($lead->stage->name ?? 'New');
        }

        $result = $this->ai->generateFollowup($context, $request->previous_email);

        return response()->json([
            'success' => true,
            'content' => $result,
        ]);
    }

    public function generateProposal(Request $request)
    {
        $request->validate([
            'context' => 'required|string|min:10',
            'lead_id' => 'nullable|exists:leads,id',
        ]);

        $context = $request->context;

        if ($request->lead_id) {
            $lead = Lead::find($request->lead_id);
            $context .= "\n\nLead Details:\nName: " . $lead->name .
                        "\nCompany: " . ($lead->company ?? 'Not provided') .
                        "\nValue: $" . ($lead->value ?? 0);
        }

        $result = $this->ai->generateProposal($context);

        return response()->json([
            'success' => true,
            'content' => $result,
        ]);
    }

    public function generateSummary(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
        ]);

        $lead = Lead::with(['stage', 'assignedTo', 'notes'])
            ->find($request->lead_id);

        $leadData = "Name: " . $lead->name .
                    "\nCompany: " . ($lead->company ?? 'Not provided') .
                    "\nEmail: " . ($lead->email ?? 'Not provided') .
                    "\nPhone: " . ($lead->phone ?? 'Not provided') .
                    "\nStage: " . ($lead->stage->name ?? 'New') .
                    "\nValue: $" . ($lead->value ?? 0) .
                    "\nSource: " . ($lead->source ?? 'Unknown') .
                    "\nAssigned to: " . ($lead->assignedTo->name ?? 'Unassigned') .
                    "\nNotes: " . ($lead->notes->count() > 0 ? $lead->notes->pluck('note')->join("\n") : 'No notes');

        $result = $this->ai->generateSummary($leadData);

        return response()->json([
            'success' => true,
            'content' => $result,
            'lead_name' => $lead->name,
        ]);
    }
}