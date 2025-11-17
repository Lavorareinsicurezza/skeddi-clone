<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentTypes = DocumentType::where('company_id', auth()->user()->company_id)->paginate(20);
        return view('admin.document-types.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.document-types.create');
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

        DocumentType::create($validated);

        return redirect()->route('admin.document-types.index')->with('success', 'Document type created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $documentType = DocumentType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.document-types.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $documentType = DocumentType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.document-types.edit', compact('documentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $documentType = DocumentType::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'validity_year' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $documentType->update($validated);

        return redirect()->route('admin.document-types.index')->with('success', 'Document type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $documentType = DocumentType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $documentType->delete();

        return redirect()->route('admin.document-types.index')->with('success', 'Document type deleted successfully');
    }
}
