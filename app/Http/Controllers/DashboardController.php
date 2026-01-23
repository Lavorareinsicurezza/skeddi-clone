<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyCourseType;
use App\Models\CompanyVisitType;
use App\Models\Document;
use App\Models\TrainingPlanRecord;
use App\Models\OperatingLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeadlinesExport;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $operatingLocationId = $request->operating_location_id;
        $company = $user->company;
        $companyIds = Company::where('id', $user->company_id)
            ->orWhere('company_id', $user->company_id)
            ->pluck('id');

        $operatingLocations = OperatingLocation::whereIn('company_id', $companyIds)->get();
        $deadlineType = $request->deadline_type ?? 'all'; // default 'all'
        $search = $request->search; // search input

        /* TRAINING PLANS */
        $trainingPlansQuery = TrainingPlanRecord::select(
            'training_plan_records.id',
            'training_plan_records.company_id',
            'workers.surname as employee_name',
            'company_course_types.name as name',
            DB::raw("'Training Plan' as deadline_type"),
            'training_plan_records.expiration_date as expiry_date',
            'operating_locations.name as location_name'
        )
            ->leftJoin('workers', 'workers.id', 'training_plan_records.worker_id')
            ->leftJoin('company_course_types', 'company_course_types.id', 'training_plan_records.company_course_type_id')
            ->leftJoin('operating_locations', 'operating_locations.id', 'workers.operating_location_id')
            ->leftJoin('companies', 'companies.id', 'training_plan_records.company_id')
            ->whereIn('training_plan_records.company_id', $companyIds)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('training_plan_records.expiration_date', [$fromDate, $toDate]);
            })
            ->when($operatingLocationId, function ($q) use ($operatingLocationId) {
                $q->where('workers.operating_location_id', $operatingLocationId);
            });

        if ($search) {
            $trainingPlansQuery->where(function ($q) use ($search) {
                $q->where('workers.surname', 'like', "%{$search}%")
                    ->orWhere('company_course_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Training Plan'"), 'like', "%{$search}%"); // optional: search by deadline_type
            });
        }

        /* COURSES */
        $coursesQuery = CompanyCourseType::select(
            'company_course_types.id',
            'company_course_types.company_id',
            DB::raw('NULL as employee_name'),
            'company_course_types.name as name',
            DB::raw("'Course' as deadline_type"),
            DB::raw("expiration_date as expiry_date"),
            DB::raw("NULL as location_name")
        )
            ->leftJoin('companies', 'companies.id', 'company_course_types.company_id')
            ->whereIn('company_course_types.company_id', $companyIds)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween(
                    DB::raw("expiration_date"),
                    [$fromDate, $toDate]
                );
            });

        if ($search) {
            $coursesQuery->where(function ($q) use ($search) {
                $q->where('company_course_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Course'"), 'like', "%{$search}%");
            });
        }

        /* DOCUMENTS */
        $documentsQuery = Document::select(
            'documents.id',
            'documents.company_id',
            DB::raw('NULL as employee_name'),
            'documents.name as name',
            DB::raw("'Document' as deadline_type"),
            'documents.expiration_date as expiry_date',
            'operating_locations.name as location_name'
        )
            ->leftJoin('companies', 'companies.id', 'documents.company_id')
            ->leftJoin('operating_locations', 'operating_locations.id', 'documents.operating_location_id')
            ->whereIn('documents.company_id', $companyIds)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('documents.expiration_date', [$fromDate, $toDate]);
            })
            ->when($operatingLocationId, function ($q) use ($operatingLocationId) {
                $q->where(function ($qq) use ($operatingLocationId) {
                    $qq->where('documents.operating_location_id', $operatingLocationId)
                       ->orWhereNull('documents.operating_location_id');
                });
            });

        if ($search) {
            $documentsQuery->where(function ($q) use ($search) {
                $q->where('documents.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Document'"), 'like', "%{$search}%");
            });
        }

        /* VISITS */
        $visitsQuery = CompanyVisitType::select(
            'company_visit_types.id',
            'company_visit_types.company_id',
            DB::raw('NULL as employee_name'),
            'company_visit_types.name as name',
            DB::raw("'Visit Type' as deadline_type"),
            'company_visit_types.expiry_date as expiry_date',
            DB::raw("NULL as location_name")
        )
            ->leftJoin('companies', 'companies.id', 'company_visit_types.company_id')
            ->whereIn('company_visit_types.company_id', $companyIds)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('company_visit_types.expiry_date', [$fromDate, $toDate]);
            });

        if ($search) {
            $visitsQuery->where(function ($q) use ($search) {
                $q->where('company_visit_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Visit Type'"), 'like', "%{$search}%");
            });
        }

        // Apply deadline_type filter
        $queries = collect([]);
        if ($deadlineType === 'all' || $deadlineType === 'training_plan') {
            $queries->push($trainingPlansQuery);
        }
        if ($deadlineType === 'all' || $deadlineType === 'courses') {
            $queries->push($coursesQuery);
        }
        if ($deadlineType === 'all' || $deadlineType === 'documents') {
            $queries->push($documentsQuery);
        }
        if ($deadlineType === 'all' || $deadlineType === 'visits') {
            $queries->push($visitsQuery);
        }

        // Merge queries with unionAll
        $recordsQuery = $queries->shift(); // first query
        foreach ($queries as $query) {
            $recordsQuery = $recordsQuery->unionAll($query);
        }

        $records = DB::query()->fromSub($recordsQuery, 'all_records')
            ->orderBy('expiry_date', 'DESC')
            ->get();

        return view('welcome', [
            'currentCompany' => $company,
            'records' => $records,
            'selectedDeadlineType' => $deadlineType,
            'search' => $search,
            'operatingLocations' => $operatingLocations,
            'selectedOperatingLocationId' => $operatingLocationId
        ]);
    }



    /**
     * Display the deadlines page.
     *
     * @return \Illuminate\View\View
     */
    public function deadlines(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $companyId = session('selectedCompanyId');
        $operatingLocationId = $request->operating_location_id;
        $operatingLocations = OperatingLocation::where('company_id', $companyId)->get();
        $deadlineType = $request->deadline_type ?? 'all'; // default 'all'
        $search = $request->search; // search input
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        /* TRAINING PLANS */
        $trainingPlansQuery = TrainingPlanRecord::select(
            'training_plan_records.id',
            'training_plan_records.company_id',
            'workers.surname as employee_name',
            'company_course_types.name as name',
            DB::raw("'Training Plan' as deadline_type"),
            'training_plan_records.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            'workers.first_name',
            'workers.surname',
            'training_plan_records.training_date'
        )
            ->leftJoin('workers', 'workers.id', 'training_plan_records.worker_id')
            ->leftJoin('company_course_types', 'company_course_types.id', 'training_plan_records.company_course_type_id')
            ->leftJoin('operating_locations', 'operating_locations.id', 'workers.operating_location_id')
            ->leftJoin('companies', 'companies.id', 'training_plan_records.company_id')
            ->where('training_plan_records.company_id', $companyId)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('training_plan_records.expiration_date', [$fromDate, $toDate]);
            })
            ->when($operatingLocationId, function ($q) use ($operatingLocationId) {
                $q->where('workers.operating_location_id', $operatingLocationId);
            });

        if ($search) {
            $trainingPlansQuery->where(function ($q) use ($search) {
                $q->where('workers.surname', 'like', "%{$search}%")
                    ->orWhere('company_course_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Training Plan'"), 'like', "%{$search}%");
            });
        }

        /* COURSES */
        $coursesQuery = CompanyCourseType::select(
            'company_course_types.id',
            'company_course_types.company_id',
            DB::raw('NULL as employee_name'),
            'company_course_types.name as name',
            DB::raw("'Course' as deadline_type"),
            DB::raw("expiration_date as expiry_date"),
            DB::raw("NULL as location_name"),
            DB::raw("NULL as first_name"),
            DB::raw("NULL as surname"),
            DB::raw("NULL as training_date")
        )
            ->leftJoin('companies', 'companies.id', 'company_course_types.company_id')
            ->where('company_course_types.company_id', $companyId)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween(
                    DB::raw("expiration_date"),
                    [$fromDate, $toDate]
                );
            });

        if ($search) {
            $coursesQuery->where(function ($q) use ($search) {
                $q->where('company_course_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Course'"), 'like', "%{$search}%");
            });
        }

        /* DOCUMENTS */
        $documentsQuery = Document::select(
            'documents.id',
            'documents.company_id',
            DB::raw('NULL as employee_name'),
            'documents.name as name',
            DB::raw("'Document' as deadline_type"),
            'documents.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            DB::raw("NULL as first_name"),
            DB::raw("NULL as surname"),
            DB::raw("NULL as training_date")
        )
            ->leftJoin('companies', 'companies.id', 'documents.company_id')
            ->leftJoin('operating_locations', 'operating_locations.id', 'documents.operating_location_id')
            ->where('documents.company_id', $companyId)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('documents.expiration_date', [$fromDate, $toDate]);
            })
            ->when($operatingLocationId, function ($q) use ($operatingLocationId) {
                $q->where(function ($qq) use ($operatingLocationId) {
                    $qq->where('documents.operating_location_id', $operatingLocationId)
                       ->orWhereNull('documents.operating_location_id');
                });
            });

        if ($search) {
            $documentsQuery->where(function ($q) use ($search) {
                $q->where('documents.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Document'"), 'like', "%{$search}%");
            });
        }

        /* VISITS */
        $visitsQuery = CompanyVisitType::select(
            'company_visit_types.id',
            'company_visit_types.company_id',
            DB::raw('NULL as employee_name'),
            'company_visit_types.name as name',
            DB::raw("'Visit Type' as deadline_type"),
            'company_visit_types.expiry_date as expiry_date',
            DB::raw("NULL as location_name"),
            DB::raw("NULL as first_name"),
            DB::raw("NULL as surname"),
            DB::raw("NULL as training_date")
        )
            ->leftJoin('companies', 'companies.id', 'company_visit_types.company_id')
            ->where('company_visit_types.company_id', $companyId)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('company_visit_types.expiry_date', [$fromDate, $toDate]);
            });

        if ($search) {
            $visitsQuery->where(function ($q) use ($search) {
                $q->where('company_visit_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Visit Type'"), 'like', "%{$search}%");
            });
        }

        // Apply deadline_type filter
        $queries = collect([]);
        if ($deadlineType === 'all' || $deadlineType === 'training_plan') {
            $queries->push($trainingPlansQuery);
        }
        if ($deadlineType === 'all' || $deadlineType === 'courses') {
            $queries->push($coursesQuery);
        }
        if ($deadlineType === 'all' || $deadlineType === 'documents') {
            $queries->push($documentsQuery);
        }
        if ($deadlineType === 'all' || $deadlineType === 'visits') {
            $queries->push($visitsQuery);
        }

        // Merge queries with unionAll
        $recordsQuery = $queries->shift(); // first query
        foreach ($queries as $query) {
            $recordsQuery = $recordsQuery->unionAll($query);
        }

        $records = DB::query()->fromSub($recordsQuery, 'all_records')
            ->orderBy('expiry_date', 'DESC')
            ->get();

        return view('company.deadlines.index', [
            'currentCompany' => $company,
            'records' => $records,
            'operatingLocations' => $operatingLocations,
            'selectedOperatingLocationId' => $operatingLocationId,
            'selectedDeadlineType' => $deadlineType,
            'search' => $search,
        ]);
    }

    /**
     * Export deadlines (training plans) to Excel
     */
    public function exportDeadlines(Request $request)
    {
        $operatingLocationId = $request->operating_location_id ? (int)$request->operating_location_id : null;
        return Excel::download(new DeadlinesExport($operatingLocationId), 'deadlines-' . date('Y-m-d-His') . '.xlsx');
    }

    /**
     * Get all companies for AJAX request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies()
    {
        $user = Auth::user();
        $baseQuery = Company::select('id', 'name', 'phone')->where('company_id', $user->company_id);

        if ($user->hasRole('superadmin') || $user->can('view companies')) {
            $companies = $baseQuery->get();
        } else {
            $companies = $baseQuery
                ->whereJsonContains('contacts', $user->email)
                ->get();
        }

        return response()->json([
            'companies' => $companies
        ]);
    }

    /**
     * Select a company and store it in session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectCompany(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'company_name' => 'required|string'
        ]);

        $user = Auth::user();
        $baseQuery = Company::select('id')->where('company_id', $user->company_id);
        if ($user->hasRole('superadmin') || $user->can('view companies')) {
            $allowedIds = $baseQuery->pluck('id')->toArray();
        } else {
            $allowedIds = $baseQuery
                ->whereJsonContains('contacts', $user->email)
                ->pluck('id')
                ->toArray();
        }

        if (!in_array((int) $validated['company_id'], $allowedIds, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized company selection'
            ], 403);
        }

        // Store in session
        session([
            'selectedCompanyId' => $validated['company_id'],
            'selectedCompanyName' => $validated['company_name']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Company selected successfully'
        ]);
    }
}
