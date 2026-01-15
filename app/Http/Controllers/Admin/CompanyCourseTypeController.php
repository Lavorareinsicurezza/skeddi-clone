<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyCourseType;
use App\Models\CourseType;
use Illuminate\Http\Request;

class CompanyCourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyCourseTypes = CompanyCourseType::query()->company()->with('courseType')->paginate(20);
        return view('company.course-type.index', compact('companyCourseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseTypes = CourseType::all();
        return view('company.course-type.create', compact('courseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_type_id' => 'required|exists:course_types,id',
            'generic_column_name' => 'nullable|string|max:255',
            'expiration_column_name' => 'nullable|string|max:255',
            'is_generic' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $companyId = session('selectedCompanyId');
        $courseType = CourseType::findOrFail($request->input('course_type_id'));
        $validityYears = (int) ($courseType->validity_year ?? 0);

        $today = \Carbon\Carbon::today();

        $expirationDate = $today->copy()->addYears($validityYears);
        // return [$expirationDate];
        CompanyCourseType::create([
            'company_id' => $companyId,
            'course_type_id' => $request->input('course_type_id'),
            'name' => $courseType->course_name,
            'validity_years' => $courseType->validity_year,
            'expiration_date' => $expirationDate,
            'generic_column_name' => $request->input('generic_column_name'),
            'expiration_column_name' => $request->input('expiration_column_name'),
            'is_generic' => $request->has('is_generic') ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.company-course-types.index')->with('success', __('lang.course_type_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companyCourseType = CompanyCourseType::with(['trainingPlanRecords' => function ($q) {
            return $q->with('worker');
        }])->company()->with('courseType')->findOrFail($id);
        return view('company.course-type.show', compact('companyCourseType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyCourseType = CompanyCourseType::with(['trainingPlanRecords' => function ($q) {
            return $q->with('worker');
        }])->company()->with('courseType')->findOrFail($id);
        $courseTypes = CourseType::all();
        return view('company.course-type.create', compact('companyCourseType', 'courseTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'course_type_id' => 'required|exists:course_types,id',
            'generic_column_name' => 'nullable|string|max:255',
            'expiration_column_name' => 'nullable|string|max:255',
            'is_generic' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $companyCourseType = CompanyCourseType::query()->company()->findOrFail($id);
        $courseType = CourseType::findOrFail($request->input('course_type_id'));

        $validityYears = (int) ($courseType->validity_year ?? 0);

        $today = \Carbon\Carbon::today();

        $expirationDate = $today->copy()->addYears($validityYears);

        $companyCourseType->update([
            'course_type_id' => $request->input('course_type_id'),
            'name' => $courseType->course_name,
            'validity_years' => $courseType->validity_year,
            'generic_column_name' => $request->input('generic_column_name'),
            'expiration_column_name' => $request->input('expiration_column_name'),
            'expiration_date' => $expirationDate,
            'is_generic' => $request->has('is_generic') ? 1 : 0,
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.company-course-types.index')->with('success', __('lang.course_type_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $companyCourseType = CompanyCourseType::query()->company()->findOrFail($id);
        $companyCourseType->delete();

        return redirect()->route('admin.company-course-types.index')->with('success', __('lang.course_type_deleted_successfully'));
    }

    /**
     * Export company course types data.
     */
    public function export()
    {
        // TODO: Implement export logic
        return redirect()->route('admin.company-course-types.index')->with('success', __('lang.data_exported_successfully'));
    }
}
