<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
   public function index(Request $request)
{
    $query = Company::where('tenant_id', Auth::user()->tenant_id);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('industry', 'LIKE', "%{$search}%");
        });
    }

    $companies = $query->withCount('contacts')
        ->latest()
        ->paginate(15);

    return view('companies.index', compact('companies'));
}

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'industry' => 'nullable|string|max:100',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;
        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created.');
    }

    public function show(Company $company)
    {
        $this->authorizeTenant($company);
        $company->load('contacts');
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $this->authorizeTenant($company);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeTenant($company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'industry' => 'nullable|string|max:100',
        ]);

        $company->update($validated);
        return redirect()->route('companies.index')->with('success', 'Company updated.');
    }

    public function destroy(Company $company)
    {
        $this->authorizeTenant($company);
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted.');
    }

    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}