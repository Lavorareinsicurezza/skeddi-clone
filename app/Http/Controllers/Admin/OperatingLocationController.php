<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperatingLocation;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatingLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operatingLocations = OperatingLocation::query()->company()
            ->with('company')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.operating-locations.index', compact('operatingLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::where('company_id', Auth::user()->company_id)
            ->orderBy('name')
            ->get();

        return view('admin.operating-locations.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'address_street' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'address_postal' => 'nullable|string|max:20',
            'address_city' => 'nullable|string|max:100',
            'site_contact_name' => 'nullable|string|max:255',
            'site_contact_phone' => 'nullable|string|max:20',
            'site_contact_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ], [
            'company_id.required' => 'Please select a company.',
            'name.required' => 'Location name is required.',
            'site_contact_email.email' => 'Please enter a valid email address for site contact.'
        ]);

        // Ensure the company belongs to the user's company hierarchy
        $company = Company::findOrFail($validated['company_id']);
        if ($company->company_id !== Auth::user()->company_id) {
            return redirect()->back()->with('error', 'Invalid company selection.');
        }

        OperatingLocation::create($validated);

        return redirect()->route('admin.operating-locations.index')
            ->with('success', 'Operating location created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $operatingLocation = OperatingLocation::query()->company()->findOrFail($id);

        return view('admin.operating-locations.show', compact('operatingLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $operatingLocation = OperatingLocation::query()->company()->findOrFail($id);

        $companies = Company::where('company_id', Auth::user()->company_id)
            ->orderBy('name')
            ->get();

        return view('admin.operating-locations.edit', compact('operatingLocation', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $operatingLocation = OperatingLocation::query()->company()->findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'address_street' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'address_postal' => 'nullable|string|max:20',
            'address_city' => 'nullable|string|max:100',
            'site_contact_name' => 'nullable|string|max:255',
            'site_contact_phone' => 'nullable|string|max:20',
            'site_contact_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ], [
            'company_id.required' => 'Please select a company.',
            'name.required' => 'Location name is required.',
            'site_contact_email.email' => 'Please enter a valid email address for site contact.'
        ]);

        // Ensure the company belongs to the user's company hierarchy
        $company = Company::findOrFail($validated['company_id']);
        if ($company->company_id !== Auth::user()->company_id) {
            return redirect()->back()->with('error', 'Invalid company selection.');
        }

        $operatingLocation->update($validated);

        return redirect()->route('admin.operating-locations.index')
            ->with('success', 'Operating location updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $operatingLocation = OperatingLocation::query()->company()->findOrFail($id);

        // Check if location has workers
        if ($operatingLocation->workers()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete operating location that has employees assigned.');
        }

        // Check if location has documents
        if ($operatingLocation->documents()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete operating location that has documents.');
        }

        $operatingLocation->delete();

        return redirect()->route('admin.operating-locations.index')
            ->with('success', 'Operating location deleted successfully');
    }
}
