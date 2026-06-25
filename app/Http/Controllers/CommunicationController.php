<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use App\Models\Company;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,email,meeting,note',
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'duration' => 'nullable|string|max:50',
            'direction' => 'nullable|string|max:20',
            'communicable_type' => 'required|in:company,lead',
            'communicable_id' => 'required|integer',
        ]);

        // Map communicable_type to full class
        $map = [
            'company' => Company::class,
            'lead' => Lead::class,
        ];
        $validated['communicable_type'] = $map[$request->communicable_type];

        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['user_id'] = Auth::id();

        // Verify the parent exists and user has access
        $parent = $validated['communicable_type']::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($validated['communicable_id']);
        $this->authorizeTenant($parent);

        Communication::create($validated);

        return redirect()->back()->with('success', 'Communication logged successfully.');
    }

    public function destroy(Communication $communication)
    {
        $this->authorizeTenant($communication);
        $communication->delete();
        return redirect()->back()->with('success', 'Communication deleted.');
    }

    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}