<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyCourseType;
use App\Models\CourseType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CourceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseTypes = CourseType::where('company_id', auth()->user()->company_id)->paginate(20);
        return view('admin.course-types.index', compact('courseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.course-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'validity_year' => 'required|integer|min:1',
            'generic' => 'nullable|string|max:255',
            'expiration' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Convert expiration using Carbon if provided
        if (!empty($validated['expiration'])) {
            $validated['expiration'] = Carbon::parse($validated['expiration'])->format('Y-m-d');
        }

        // Add company_id from authenticated user
        $validated['company_id'] = auth()->user()->company_id;

        CourseType::create($validated);

        return redirect()->route('admin.course-types.index')->with('success', 'Course type created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $courseType = CourseType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.course-types.show', compact('courseType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $courseType = CourseType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('admin.course-types.edit', compact('courseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $courseType = CourseType::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'validity_year' => 'required|integer|min:1',
            'generic' => 'nullable|string|max:255',
            'expiration' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Convert expiration using Carbon if provided
        if (!empty($validated['expiration'])) {
            $validated['expiration'] = Carbon::parse($validated['expiration'])->format('Y-m-d');
        }

        $courseType->update($validated);

        // Propagate changes to company course types: inherit name and validity, recalc expiration
        $validityYears = (int) $courseType->validity_year;
        $today = Carbon::today();
        $newExpiration = $today->copy()->addYears($validityYears);

        CompanyCourseType::where('course_type_id', $courseType->id)
            ->update([
                'name' => $courseType->course_name,
                'validity_years' => $courseType->validity_year,
                'expiration_date' => $newExpiration,
            ]);

        return redirect()->route('admin.course-types.index')->with('success', 'Course type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $courseType = CourseType::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $courseType->delete();

        return redirect()->route('admin.course-types.index')->with('success', 'Course type deleted successfully');
    }
}
