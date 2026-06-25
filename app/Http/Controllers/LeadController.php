<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\User;
use App\Http\Requests\LeadStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
  public function index(Request $request)
{
    $query = Lead::where('tenant_id', Auth::user()->tenant_id);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('company', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    $leads = $query->with(['stage', 'assignedTo', 'createdBy'])
        ->latest()
        ->paginate(15);

    return view('leads.index', compact('leads'));
}

    public function create()
    {
        $stages = PipelineStage::where('tenant_id', Auth::user()->tenant_id)->orderBy('order')->get();
        $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('leads.create', compact('stages', 'users'));
    }

    public function store(LeadStoreRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = Auth::user()->tenant_id;
        $data['created_by'] = Auth::id();
        $lead = Lead::create($data);

        // --- GUARANTEED NOTIFICATION ---
        if ($lead->assigned_to) {
            $this->createNotification(
                $lead->assigned_to,
                'New lead assigned: ' . $lead->name,
                'Lead from ' . ($lead->company ?? 'Unknown company') . ' assigned to you.',
                route('leads.show', $lead)
            );
        }

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorizeTenant($lead);
        $lead->load(['notes.user', 'files.uploadedBy', 'stage', 'assignedTo']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authorizeTenant($lead);
        $stages = PipelineStage::where('tenant_id', Auth::user()->tenant_id)->orderBy('order')->get();
        $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('leads.edit', compact('lead', 'stages', 'users'));
    }

    public function update(LeadStoreRequest $request, Lead $lead)
    {
        $this->authorizeTenant($lead);

        $oldAssignedId = $lead->assigned_to;
        $oldStageId = $lead->stage_id;

        $lead->update($request->validated());
        $lead->refresh();

        // --- NOTIFICATION: Assigned user changed ---
        if ($lead->assigned_to && $lead->assigned_to != $oldAssignedId) {
            $this->createNotification(
                $lead->assigned_to,
                'Lead assigned to you: ' . $lead->name,
                'Lead from ' . ($lead->company ?? 'Unknown') . ' has been assigned to you.',
                route('leads.show', $lead)
            );
        }

        // --- NOTIFICATION: Stage changed ---
        if ($lead->stage_id && $lead->stage_id != $oldStageId && $lead->assigned_to) {
            $oldStageName = $oldStageId ? PipelineStage::find($oldStageId)?->name ?? 'None' : 'None';
            $newStageName = $lead->stage->name ?? 'None';
            if ($oldStageName !== $newStageName) {
                $this->createNotification(
                    $lead->assigned_to,
                    'Lead stage changed: ' . $lead->name,
                    'Lead "' . $lead->name . '" moved from "' . $oldStageName . '" to "' . $newStageName . '".',
                    route('leads.show', $lead)
                );
            }
        }

        return redirect()->route('leads.index')->with('success', 'Lead updated.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorizeTenant($lead);
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted.');
    }

    // ====== Import/Export ======
    public function export()
    {
        return Excel::download(new LeadsExport, 'leads.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);
        Excel::import(new LeadsImport, $request->file('file'));
        return redirect()->back()->with('success', 'Leads imported.');
    }

    // ====== Notes & Attachments ======
    public function addNote(Request $request, Lead $lead)
    {
        $this->authorizeTenant($lead);
        $request->validate(['note' => 'required|string']);
        $lead->notes()->create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'note' => $request->note,
        ]);
        return redirect()->back()->with('success', 'Note added.');
    }

    public function uploadFile(Request $request, Lead $lead)
    {
        $this->authorizeTenant($lead);
        $request->validate(['file' => 'required|file|max:10240']);
        $file = $request->file('file');
        $path = $file->store('leads/' . $lead->id, 'public');
        $lead->files()->create([
            'tenant_id' => Auth::user()->tenant_id,
            'filename' => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
            'uploaded_by' => Auth::id(),
        ]);
        return redirect()->back()->with('success', 'File uploaded.');
    }

    public function deleteFile(LeadFile $file)
    {
        $this->authorizeTenant($file->lead);
        Storage::disk('public')->delete($file->path);
        $file->delete();
        return redirect()->back()->with('success', 'File deleted.');
    }

    // ====== GUARANTEED NOTIFICATION METHOD ======
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

    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}