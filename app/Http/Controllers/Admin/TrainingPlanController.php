<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\CompanyCourseType;
use App\Models\TrainingPlanRecord;
use Illuminate\Http\Request;

class TrainingPlanController extends Controller
{
    /**
     * Display the training plan.
     */
    public function index()
    {
        $companyId = session('selectedCompanyId');

        // Get all active workers for this company
        $workers = Worker::query()->company()->where('is_active', true)->get();

        // Get all company course types
        $courseTypes = CompanyCourseType::query()->company()->with('courseType')->get();

        // Get all existing training plan records
        $trainingRecords = TrainingPlanRecord::query()
            ->company()
            ->get()
            ->keyBy(function ($item) {
                return $item->worker_id . '_' . $item->company_course_type_id;
            });

        return view('company.training-plan.index', compact('workers', 'courseTypes', 'trainingRecords'));
    }

    /**
     * Save training plan data.
     */
    public function save(Request $request)
    {
        $companyId = session('selectedCompanyId');

        $request->validate([
            'records' => 'required|array',
            'records.*.worker_id' => 'required|exists:workers,id',
            'records.*.course_type_id' => 'required|exists:company_course_types,id',
            'records.*.training_date' => 'nullable|date',
            'records.*.expiration_date' => 'nullable|date',
            'records.*.to_be_scheduled' => 'sometimes|boolean',
        ]);

        foreach ($request->input('records') as $record) {
            if($record['training_date'] === null && $record['expiration_date'] === null && !isset($record['to_be_scheduled'])) {
                // If no data is provided, skip this record
                continue;
            }
            TrainingPlanRecord::updateOrCreate(
                [
                    'worker_id' => $record['worker_id'],
                    'company_course_type_id' => $record['course_type_id'],
                ],
                [
                    'company_id' => $companyId,
                    'training_date' => $record['training_date'] ?? null,
                    'expiration_date' => $record['expiration_date'] ?? null,
                    'to_be_scheduled' => isset($record['to_be_scheduled']) ? 1 : 0,
                ]
            );
        }

        return redirect()->route('admin.training-plan.index')->with('success', __('lang.training_plan_saved_successfully'));
    }
}
