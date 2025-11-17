<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitType;
use Illuminate\Http\Request;

class VisitTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visitTypes = VisitType::where('company_id', auth()->user()->company_id)->paginate(20);
        return view('admin.visit-types.index', compact('visitTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.visit-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'validity_year' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Add company_id from authenticated user
        $validated['company_id'] = auth()->user()->company_id;

        VisitType::create($validated);

        return redirect()->route('admin.visit-types.index')->with('success', 'Visit type created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $visitType = VisitType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.visit-types.show', compact('visitType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $visitType = VisitType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.visit-types.edit', compact('visitType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $visitType = VisitType::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'validity_year' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $visitType->update($validated);

        return redirect()->route('admin.visit-types.index')->with('success', 'Visit type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $visitType = VisitType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $visitType->delete();

        return redirect()->route('admin.visit-types.index')->with('success', 'Visit type deleted successfully');
    }
}
