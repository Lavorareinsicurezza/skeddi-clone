<?php

namespace App\Http\Controllers;

use App\Models\CompanyVisitType;
use App\Models\VisitType;
use Illuminate\Http\Request;

class CompanyVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visitTypes = CompanyVisitType::query()->company()->with('visitType')->paginate(20);
        return view('company.visit-type.index', compact('visitTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $visitTypes = VisitType::query()->company()->get();
        return view('company.visit-type.create', compact('visitTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validated = $request->validate([
            'visit_type_id'   => 'required|exists:visit_types,id',
            'name'            => 'required|string|max:255',
            'specific_name'   => 'nullable|string|max:255',
            'expiry_date'     => 'nullable|date',
            'notes'           => 'nullable|string',
        ]);

        $companyId = session('selectedCompanyId');

        CompanyVisitType::create([
            'company_id' => $companyId,
            'visit_type_id' =>  $request->visit_type_id,
            'name' =>  $request->name,
            'specific_name' =>  $request->specific_name,
            'expiry_date' =>  $request->expiry_date,
            'notes' =>  $request->notes
        ]);

        return redirect()->route('admin.company-visit-types.index')
            ->with('success', __('lang.visit_type_created_successfully'));
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
        $visitTypes = VisitType::query()->company()->get();
        $companyVisitType = CompanyVisitType::query()->company()->findOrFail($id);

        return view('company.visit-type.create', compact('visitTypes', 'companyVisitType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'visit_type_id'   => 'required|exists:visit_types,id',
            'name'            => 'required|string|max:255',
            'specific_name'   => 'nullable|string|max:255',
            'expiry_date'     => 'nullable|date',
            'notes'           => 'nullable|string',
        ]);

        $companyVisitType = CompanyVisitType::query()->company()->findOrFail($id);
        $companyVisitType->update([
            'visit_type_id' =>  $request->visit_type_id,
            'name' =>  $request->name,
            'specific_name' =>  $request->specific_name,
            'expiry_date' =>  $request->expiry_date,
            'notes' =>  $request->notes
        ]);

        return redirect()->route('admin.company-visit-types.index')
            ->with('success', __('lang.visit_type_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $visitType = CompanyVisitType::query()->company()->findOrFail($id);
        $visitType->delete();

        return redirect()->route('admin.company-visit-types.index')->with('success', __('lang.visit_type_deleted_successfully'));
    }
}
