<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        return view('settings.index', compact('tenant'));
    }

    public function update(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'date_format' => 'nullable|string|max:20',
        ]);

        // Merge config fields
        $config = $tenant->config ?? [];
        $config['timezone'] = $request->timezone ?? 'UTC';
        $config['currency'] = $request->currency ?? 'USD';
        $config['date_format'] = $request->date_format ?? 'Y-m-d';

        $tenant->update([
            'name' => $request->company_name,
            'email' => $request->email,
            'config' => $config,
        ]);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}