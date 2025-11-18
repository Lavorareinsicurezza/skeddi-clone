<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CompanyDocumentsExport;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CompanyDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::query()->company()->paginate(20);
        return view('company.document.index', compact('documents'));
    }

    public function export()
    {
        return Excel::download(new CompanyDocumentsExport, 'company-documents-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = DocumentType::all();
        return view('company.document.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'scheduling_note' => 'nullable|string',
            'expiration_date' => 'nullable|date',
            'to_schedule' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

            $companyId = session('selectedCompanyId');

        Document::create([
            'company_id' => $companyId,
            'document_type_id' => $request->input('document_type_id'),
            'name' => $request->input('name'),
            'scheduling_note' => $request->input('scheduling_note'),
            'expiration_date' => $request->input('expiration_date'),
            'to_schedule' => $request->has('to_schedule') ? $request->input('to_schedule') : false,
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.company-documents.index')->with('success', 'Document created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $document = Document::findOrFail($id);

        $types = DocumentType::all();
        return view('company.document.create', compact('document', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'scheduling_note' => 'nullable|string',
            'expiration_date' => 'nullable|date',
            'to_schedule' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $document = Document::findOrFail($id);
        $document->update([
            'document_type_id' => $request->input('document_type_id'),
            'name' => $request->input('name'),
            'scheduling_note' => $request->input('scheduling_note'),
            'expiration_date' => $request->input('expiration_date'),
            'to_schedule' => $request->has('to_schedule') ? $request->input('to_schedule') : false,
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.company-documents.index')->with('success', 'Document updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $document = Document::query()->company()->findOrFail($id);
        $document->delete();

        return redirect()->route('admin.company-documents.index')->with('success', 'Document deleted successfully');
    }
}
