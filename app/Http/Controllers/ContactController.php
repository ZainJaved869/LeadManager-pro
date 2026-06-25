<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create(Company $company)
    {
        $this->authorizeTenant($company);
        return view('contacts.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizeTenant($company);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['company_id'] = $company->id;
        $validated['is_primary'] = $request->has('is_primary');

        Contact::create($validated);
        return redirect()->route('companies.show', $company)->with('success', 'Contact added.');
    }

    public function edit(Company $company, Contact $contact)
    {
        $this->authorizeTenant($company);
        $this->authorizeTenant($contact);
        return view('contacts.edit', compact('company', 'contact'));
    }

    public function update(Request $request, Company $company, Contact $contact)
    {
        $this->authorizeTenant($company);
        $this->authorizeTenant($contact);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->has('is_primary');
        $contact->update($validated);
        return redirect()->route('companies.show', $company)->with('success', 'Contact updated.');
    }

    public function destroy(Company $company, Contact $contact)
    {
        $this->authorizeTenant($company);
        $this->authorizeTenant($contact);
        $contact->delete();
        return redirect()->route('companies.show', $company)->with('success', 'Contact deleted.');
    }

    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}