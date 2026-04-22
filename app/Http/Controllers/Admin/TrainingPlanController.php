<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseRenewalLog;
use App\Models\Worker;
use App\Models\CompanyCourseType;
use App\Models\CompanyVisitType;
use App\Models\Document;
use App\Models\TrainingPlanRecord;
use App\Models\TrainingPlanDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainingPlanExport;

class TrainingPlanController extends Controller
{
    /**
     * Display the training plan.
     */
    public function index()
    {
        $companyId = session('selectedCompanyId');

        // Get all active workers for this company
        $workers = Worker::query()->company()->where('is_active', true)->with('operatingLocation')->get();

        // Get all company course types
        $courseTypes = CompanyCourseType::query()->company()->with('courseType')->orderBy('sort_order')->orderBy('id')->get();

        // Get all existing training plan records
        $trainingRecords = TrainingPlanRecord::query()
            ->company()
            ->with('documents')
            ->get()
            ->keyBy(function ($item) {
                return $item->worker_id . '_' . $item->company_course_type_id;
            });

        return view('company.training-plan.index', compact('workers', 'courseTypes', 'trainingRecords'));
    }

    /**
     * Export training plan records to Excel.
     */
    public function export()
    {
        return Excel::download(new TrainingPlanExport, 'training-plan-' . date('Y-m-d-His') . '.xlsx');
    }

    /**
     * Save training plan data.
     */
    public function save(Request $request)
    {
        // return $request->all();
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
            if ($record['training_date'] === null && $record['expiration_date'] === null && !isset($record['to_be_scheduled'])) {
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

    /**
     * Get documents for a specific worker and course type.
     */
    public function getDocuments(Request $request)
    {
        $companyId = session('selectedCompanyId');

        $documents = TrainingPlanDocument::where('company_id', $companyId)
            ->where('worker_id', $request->worker_id)
            ->where('company_course_type_id', $request->course_type_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($documents);
    }

    /**
     * Store a new document.
     */
    public function storeDocument(Request $request)
    {
        $companyId = session('selectedCompanyId');

        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'course_type_id' => 'required|exists:company_course_types,id',
            'file' => 'nullable|file|max:10240',
            'note' => 'nullable|string',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('training_plan_documents', $fileName, 'public');
        }

        // Get or create the training plan record
        $trainingPlanRecord = TrainingPlanRecord::where('company_id', $companyId)
            ->where('worker_id', $request->worker_id)
            ->where('company_course_type_id', $request->course_type_id)
            ->first();

        TrainingPlanDocument::create([
            'company_id' => $companyId,
            'worker_id' => $request->worker_id,
            'company_course_type_id' => $request->course_type_id,
            'training_plan_record_id' => $trainingPlanRecord?->id,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'note' => $request->note,
        ]);

        // Fetch updated notes for tooltip
        $notes = TrainingPlanDocument::where('company_id', $companyId)
            ->where('worker_id', $request->worker_id)
            ->where('company_course_type_id', $request->course_type_id)
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->pluck('note')
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'message' => __('lang.document_saved_successfully'),
            'notes' => $notes
        ]);
    }

    /**
     * Delete a document.
     */
    public function deleteDocument($id)
    {
        $companyId = session('selectedCompanyId');

        $document = TrainingPlanDocument::where('company_id', $companyId)
            ->where('id', $id)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => __('lang.document_not_found')
            ], 404);
        }

        // Delete file from storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => __('lang.document_deleted_successfully')
        ]);
    }

    /**
     * Download a document.
     */
    public function downloadDocument($id)
    {
        $companyId = session('selectedCompanyId');

        $document = TrainingPlanDocument::where('company_id', $companyId)
            ->where('id', $id)
            ->first();

        if (!$document || !$document->file_path) {
            abort(404);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Renew a training plan record.
     */
    // public function renew(Request $request)
    // {
    //     $companyId = session('selectedCompanyId');

    //     $request->validate([
    //         'training_plan_id' => 'required|exists:training_plan_records,id',
    //         'renewal_date' => 'required|date',
    //     ]);

    //     $trainingPlan = TrainingPlanRecord::with('companyCourseType.courseType')
    //         ->where('company_id', $companyId)
    //         ->where('id', $request->training_plan_id)
    //         ->first();

    //     if (!$trainingPlan) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => __('lang.training_plan_not_found')
    //         ], 404);
    //     }

    //     // Update the training date and calculate new expiration date
    //     $trainingPlan->training_date = $request->renewal_date;

    //     // Calculate expiration date based on course validity
    //     // First check if companyCourseType has validity_years, then courseType, default to 1 year
    //     $validityYears = 1;

    //     $previousExpiryDate = $trainingPlan->expiration_date;

    //     if ($trainingPlan->companyCourseType) {
    //         if ($trainingPlan->companyCourseType->validity_years) {
    //             $validityYears = $trainingPlan->companyCourseType->validity_years;
    //         } elseif ($trainingPlan->companyCourseType->courseType && $trainingPlan->companyCourseType->courseType->validity_year) {
    //             $validityYears = $trainingPlan->companyCourseType->courseType->validity_year;
    //         }
    //     }

    //     $trainingPlan->expiration_date = \Carbon\Carbon::parse($request->renewal_date)->addYears($validityYears);
    //     $trainingPlan->to_be_scheduled = false;
    //     $trainingPlan->save();

    //     CourseRenewalLog::create([
    //         'company_id'              => $companyId,
    //         'training_plan_record_id' => $trainingPlan->id,
    //         'worker_id'               => $trainingPlan->worker_id,
    //         'company_course_type_id'  => $trainingPlan->company_course_type_id,

    //         'managed_by'              => Auth::user()->name, // or responsible person
    //         'subject'                 => $trainingPlan->companyCourseType->specific_name
    //             ?? $trainingPlan->companyCourseType->name,

    //         'previous_expiry_date'    => $previousExpiryDate,
    //         'course_update_date'      => $request->renewal_date,
    //         'new_expiry_date'         => $trainingPlan->expiration_date,
    //         'renewal_operation_date'  => now()->toDateString(),
    //         'renewed_by'              => Auth::id(),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => __('lang.course_renewed_successfully')
    //     ]);
    // }

    public function renew(Request $request)
    {
        $companyId = session('selectedCompanyId');

        $request->validate([
            'id'            => 'required|integer',
            'deadline_type' => 'required|string',
            'renewal_date'  => 'required|date',
        ]);

        $deadlineType = $request->deadline_type;
        $id = $request->id;

        $item = null;
        $previousExpiry = null;
        $newExpiry = null;
        $subject = null;

        switch ($deadlineType) {

            case 'Training Plan':

                $item = TrainingPlanRecord::with('companyCourseType')
                    ->where('id', $id)
                    ->first();

                if (!$item) {
                    return response()->json(['success' => false, 'message' => 'Training record not found'], 404);
                }

                $validityYears = (float) $item->companyCourseType->validity_years ?? 1;

                $previousExpiry = $item->expiration_date;

                $item->training_date = $request->renewal_date;
                $newExpiry = \Carbon\Carbon::parse($request->renewal_date)->addYears($validityYears);
                $item->expiration_date = $newExpiry;
                $item->to_be_scheduled = false;
                $item->save();

                $subject = $item->companyCourseType->specific_name ?? $item->companyCourseType->name;

                break;


            case 'Course':

                $item = CompanyCourseType::where('id', $id)->first();

                if (!$item) {
                    return response()->json(['success' => false, 'message' => 'Course not found'], 404);
                }

                $validityYears = (float) $item->validity_years ?? 1;

                $previousExpiry = $item->expiration_date;

                $newExpiry = \Carbon\Carbon::parse($request->renewal_date)->addYears($validityYears);

                $item->expiration_date = $newExpiry;
                $item->save();

                $subject = $item->specific_name ?? $item->name;

                break;


            case 'Document':

                $item = Document::where('id', $id)->first();

                if (!$item) {
                    return response()->json(['success' => false, 'message' => 'Document not found'], 404);
                }

                $previousExpiry = $item->expiration_date;
                $item->expiration_date = $newExpiry = $request->renewal_date;
                $item->save();

                $subject = $item->name;

                break;


            case 'Visit Type':

                $item = CompanyVisitType::where('id', $id)->first();

                if (!$item) {
                    return response()->json(['success' => false, 'message' => 'Visit record not found'], 404);
                }

                $previousExpiry = $item->expiry_date;
                $item->expiry_date = $newExpiry = $request->renewal_date;
                $item->save();

                $subject = $item->name;

                break;


            default:
                return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }



        // >>> LOG ENTRY FOR ALL TYPES <<<
        CourseRenewalLog::create([
            'company_id'              => $companyId,
            'item_id' =>  $item->id,
            'worker_id'               => $deadlineType === 'Training Plan' ? $item->worker_id : null,
            'company_course_type_id'  => ($deadlineType === 'Course' || $deadlineType === 'Training Plan') ? $item->company_course_type_id : null,

            'deadline_type'           => $deadlineType,
            'managed_by'              => Auth::user()->name,
            'renewed_by'              => Auth::id(),

            'subject'                 => $subject,
            'previous_expiry_date'    => $previousExpiry,
            'course_update_date'      => $request->renewal_date,
            'new_expiry_date'         => $newExpiry,
            'renewal_operation_date'  => now()->toDateString(),
        ]);



        return response()->json([
            'success' => true,
            'message' => 'Renewed successfully!',
        ]);
    }
}
