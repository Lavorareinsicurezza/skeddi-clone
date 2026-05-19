<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyCourseType;
use App\Models\CompanyVisitType;
use App\Models\Document;
use App\Models\TrainingPlanRecord;
use App\Models\OperatingLocation;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeadlinesExport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Carbon\Carbon;

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
            'companies.name as company_name',
            DB::raw("CONCAT(workers.first_name, ' ', workers.surname) as employee_name"),
            'company_course_types.name as name',
            DB::raw("'Training Plan' as deadline_type"),
            'training_plan_records.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            'training_plan_records.training_date'
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
                    ->orWhere('workers.first_name', 'like', "%{$search}%")
                    ->orWhere('company_course_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Training Plan'"), 'like', "%{$search}%"); // optional: search by deadline_type
            });
        }

        /* DOCUMENTS */
        $documentsQuery = Document::select(
            'documents.id',
            'documents.company_id',
            'companies.name as company_name',
            DB::raw('NULL as employee_name'),
            'documents.name as name',
            DB::raw("'Document' as deadline_type"),
            'documents.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            DB::raw("NULL as training_date")
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
            'companies.name as company_name',
            DB::raw('NULL as employee_name'),
            'company_visit_types.name as name',
            DB::raw("'Visit Type' as deadline_type"),
            'company_visit_types.expiry_date as expiry_date',
            DB::raw("NULL as location_name"),
            DB::raw("NULL as training_date")
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

    public function exportDeadlines(Request $request)
    {
        $user = Auth::user();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $operatingLocationId = $request->operating_location_id;
        $companyIds = Company::where('id', $user->company_id)
            ->orWhere('company_id', $user->company_id)
            ->pluck('id');

        $deadlineType = $request->deadline_type ?? 'all'; // default 'all'
        $search = $request->search; // search input

        /* TRAINING PLANS */
        $trainingPlansQuery = TrainingPlanRecord::select(
            'training_plan_records.id',
            'training_plan_records.company_id',
            'companies.name as company_name',
            DB::raw("CONCAT(workers.first_name, ' ', workers.surname) as employee_name"),
            'company_course_types.name as name',
            DB::raw("'Training Plan' as deadline_type"),
            'training_plan_records.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            'training_plan_records.training_date'
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
                    ->orWhere('workers.first_name', 'like', "%{$search}%")
                    ->orWhere('company_course_types.name', 'like', "%{$search}%")
                    ->orWhere('companies.name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("'Training Plan'"), 'like', "%{$search}%"); // optional: search by deadline_type
            });
        }

        /* DOCUMENTS */
        $documentsQuery = Document::select(
            'documents.id',
            'documents.company_id',
            'companies.name as company_name',
            DB::raw('NULL as employee_name'),
            'documents.name as name',
            DB::raw("'Document' as deadline_type"),
            'documents.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            DB::raw("NULL as training_date")
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
            'companies.name as company_name',
            DB::raw('NULL as employee_name'),
            'company_visit_types.name as name',
            DB::raw("'Visit Type' as deadline_type"),
            'company_visit_types.expiry_date as expiry_date',
            DB::raw("NULL as location_name"),
            DB::raw("NULL as training_date")
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

        return Excel::download(new DeadlinesExport($records), 'deadlines.xlsx');
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
            'companies.name as company_name',
            DB::raw("CONCAT(workers.first_name, ' ', workers.surname) as employee_name"),
            'company_course_types.name as name',
            DB::raw("'Training Plan' as deadline_type"),
            'training_plan_records.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            'workers.first_name',
            'workers.surname',
            'training_plan_records.training_date',
            DB::raw("(SELECT note FROM training_plan_documents WHERE training_plan_record_id = training_plan_records.id AND note IS NOT NULL AND note != '' ORDER BY created_at DESC LIMIT 1) as notes")
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

        /* DOCUMENTS */
        $documentsQuery = Document::select(
            'documents.id',
            'documents.company_id',
            'companies.name as company_name',
            DB::raw('NULL as employee_name'),
            'documents.name as name',
            DB::raw("'Document' as deadline_type"),
            'documents.expiration_date as expiry_date',
            'operating_locations.name as location_name',
            DB::raw("NULL as first_name"),
            DB::raw("NULL as surname"),
            DB::raw("NULL as training_date"),
            DB::raw("NULL as notes")
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
            'companies.name as company_name',
            DB::raw('NULL as employee_name'),
            'company_visit_types.name as name',
            DB::raw("'Visit Type' as deadline_type"),
            'company_visit_types.expiry_date as expiry_date',
            DB::raw("NULL as location_name"),
            DB::raw("NULL as first_name"),
            DB::raw("NULL as surname"),
            DB::raw("NULL as training_date"),
            DB::raw("NULL as notes")
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
     * Get all companies for AJAX request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies()
    {
        $user = Auth::user();
        $baseQuery = Company::select('id', 'name', 'phone')->when(!auth()->user()->hasRole('superadmin'), function ($q) {
            return $q->where('company_id', auth()->user()->company_id);    
        }, function ($q) use ($user) {
            $q->whereJsonContains('contacts', $user->email);
        });

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
     * Send emails to selected records from dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmails(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.module' => 'required|string|in:training_plan,course,document,visit',
            'items.*.id' => 'required|integer',
            'subject' => 'required|string',
            'body' => 'nullable|string'
        ]);

        $results = [];
        $user = Auth::user();

        foreach ($data['items'] as $item) {
            $module = $item['module'];
            $id = $item['id'];
            $record = null;
            $expiryDate = null;

            try {
                switch ($module) {
                    case 'training_plan':
                        $record = TrainingPlanRecord::with(['company', 'worker.operatingLocation', 'companyCourseType'])->find($id);
                        $expiryDate = $record->expiration_date ?? null;
                        break;
                    case 'course':
                        $record = CompanyCourseType::with('company')->find($id);
                        $expiryDate = $record->expiration_date ?? null;
                        break;
                    case 'document':
                        $record = Document::with('company')->find($id);
                        $expiryDate = $record->expiration_date ?? null;
                        break;
                    case 'visit':
                        $record = CompanyVisitType::with('company')->find($id);
                        $expiryDate = $record->expiry_date ?? null;
                        break;
                }

                if (!$record) {
                    $results[] = ['id' => $id, 'module' => $module, 'success' => false, 'message' => 'Record not found'];
                    continue;
                }

                // Check authorization
                $companyIds = Company::where('id', $user->company_id)
                    ->orWhere('company_id', $user->company_id)
                    ->pluck('id');

                if (!$companyIds->contains($record->company_id)) {
                    $results[] = ['id' => $id, 'module' => $module, 'success' => false, 'message' => 'Unauthorized'];
                    continue;
                }

                $sent = $this->sendNotificationEmail($module, $record, $expiryDate, $data['subject'], $data['body'] ?? '');
                $results[] = ['id' => $id, 'module' => $module, 'success' => $sent];
            } catch (\Exception $e) {
                Log::error('sendEmails error: ' . $e->getMessage());
                $results[] = ['id' => $id, 'module' => $module, 'success' => false, 'message' => $e->getMessage()];
            }
        }

        return response()->json(['success' => true, 'results' => $results]);
    }

    /**
     * Send notification email for a single record.
     *
     * @param string $module
     * @param mixed $record
     * @param string|null $expiryDate
     * @param string $subject
     * @param string $body
     * @return bool
     */
    private function sendNotificationEmail($module, $record, $expiryDate, $subject, $body)
    {
        try {
            $company = Company::find($record->company_id);
            if (!$company) {
                Log::warning('Company not found for record: ' . $record->id);
                return false;
            }

            $setting = Setting::where('company_id', $company->company_id)->first();
            if (!$setting) {
                Log::warning('Settings missing for company: ' . $company->id);
                return false;
            }

            $receiverCompany = Company::find($record->company_id);
            if (!$receiverCompany->main_email) {
                Log::warning('No email found for company: ' . $company->id);
                return false;
            }

            // SMTP settings (company or operating location override)
            $smtpHost = $setting->smtp_host;
            $smtpPort = $setting->smtp_port;
            $smtpUsername = $setting->smtp_username;
            $smtpPassword = $setting->smtp_password;
            $fromAddress = $setting->smtp_address ?? $setting->smtp_username;
            $fromName = config('app.name');

            // Check for Operating Location SMTP Profile Override
            if (isset($record->worker) && $record->worker && $record->worker->operatingLocation) {
                $opLocation = $record->worker->operatingLocation;
                if ($opLocation->smtp_profile_id && $opLocation->smtpProfile) {
                    $profile = $opLocation->smtpProfile;
                    $smtpHost = $profile->host;
                    $smtpPort = $profile->port;
                    $smtpUsername = $profile->username;
                    $smtpPassword = $profile->password;
                    $fromAddress = $profile->from_address ?? $profile->username;
                    if (!empty($profile->from_name)) {
                        $fromName = $profile->from_name;
                    }
                }
            }

            if (!$smtpHost || !$smtpUsername || !$smtpPassword) {
                Log::warning('SMTP settings missing for company: ' . $company->id);
                return false;
            }

            $scheme = $smtpPort == 465 ? 'smtps' : 'smtp';
            $dsn = sprintf(
                '%s://%s:%s@%s:%s',
                $scheme,
                urlencode($smtpUsername),
                urlencode($smtpPassword),
                $smtpHost,
                $smtpPort
            );

            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer(transport: $transport);

            // Calculate days left for mail type
            $mailType = 'manual';
            if ($expiryDate) {
                $days = Carbon::today()->diffInDays(Carbon::parse($expiryDate), false);
                $mailType = $days > 0 ? $days . ' days' : 'expired';
            }

            // Process body if contains placeholders
            $processedBody = $body;
            if ($body) {
                $replacements = [
                    '{company_name}' => $record->company->name ?? '',
                    '{days_left}' => $mailType,
                    '{course_name}' => $record->name ?? ($record->companyCourseType->name ?? ''),
                    '{worker_first_name}' => $record->worker->first_name ?? '',
                    '{worker_last_name}' => $record->worker->surname ?? '',
                    '{expiry_date}' => $expiryDate ? Carbon::parse($expiryDate)->format('d F Y') : '',
                ];
                $processedBody = str_replace(array_keys($replacements), array_values($replacements), $body);
            }

            // Render email template
            $html = View::make('emails.expiry-reminder', [
                'record' => $record,
                'module' => ucfirst(str_replace('_', ' ', $module)),
                'mailType' => $mailType,
                'customBody' => $processedBody
            ])->render();

            $email = (new Email())
                ->from(new Address($fromAddress, $fromName))
                ->to($receiverCompany->main_email)
                ->subject($subject)
                ->html($html);

            if ($setting->smtp_reply_to) {
                $email->replyTo($setting->smtp_reply_to);
            }

            // Send email
            try {
                $mailer->send($email);
                Log::info('Dashboard email sent module:' . $module . ' id:' . $record->id . ' to:' . $receiverCompany->main_email);
                return true;
            } catch (\Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('sendNotificationEmail error: ' . $e->getMessage());
            return false;
        }
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

    /**
     * Send WhatsApp messages to selected records from dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendWhatsApps(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.module' => 'required|string|in:training_plan,course,document,visit',
            'items.*.id' => 'required|integer',
        ]);

        $results = [];
        $user = Auth::user();
        $whatsappService = app(WhatsAppService::class);

        foreach ($data['items'] as $item) {
            $module = $item['module'];
            $id = $item['id'];
            $record = null;
            $expiryDate = null;

            try {
                switch ($module) {
                    case 'training_plan':
                        $record = TrainingPlanRecord::with(['company', 'worker.operatingLocation', 'companyCourseType'])->find($id);
                        $expiryDate = $record->expiration_date ?? null;
                        break;
                    case 'course':
                        $record = CompanyCourseType::with('company')->find($id);
                        $expiryDate = $record->expiration_date ?? null;
                        break;
                    case 'document':
                        $record = Document::with('company')->find($id);
                        $expiryDate = $record->expiration_date ?? null;
                        break;
                    case 'visit':
                        $record = CompanyVisitType::with('company')->find($id);
                        $expiryDate = $record->expiry_date ?? null;
                        break;
                }

                if (!$record) {
                    $results[] = ['id' => $id, 'module' => $module, 'success' => false, 'message' => 'Record not found'];
                    continue;
                }

                // Check authorization
                $companyIds = Company::where('id', $user->company_id)
                    ->orWhere('company_id', $user->company_id)
                    ->pluck('id');

                if (!$companyIds->contains($record->company_id)) {
                    $results[] = ['id' => $id, 'module' => $module, 'success' => false, 'message' => 'Unauthorized'];
                    continue;
                }

                $sent = $this->sendWhatsAppNotification($whatsappService, $module, $record, $expiryDate);
                $results[] = ['id' => $id, 'module' => $module, 'success' => $sent];
            } catch (\Exception $e) {
                Log::error('sendWhatsApps error: ' . $e->getMessage());
                $results[] = ['id' => $id, 'module' => $module, 'success' => false, 'message' => $e->getMessage()];
            }
        }

        return response()->json(['success' => true, 'results' => $results]);
    }

    /**
     * Send WhatsApp notification for a single record.
     *
     * @param WhatsAppService $whatsappService
     * @param string $module
     * @param mixed $record
     * @param string|null $expiryDate
     * @return bool
     */
    private function sendWhatsAppNotification($whatsappService, $module, $record, $expiryDate)
    {
        try {
            $company = Company::find($record->company_id);
            if (!$company) {
                Log::warning('Company not found for record: ' . $record->id);
                return false;
            }

            $setting = Setting::where('company_id', $company->company_id)->first();
            if (!$setting) {
                Log::warning('Settings missing for company: ' . $company->id);
                return false;
            }

            // Check if WhatsApp is configured
            if (!$setting->whatsapp_notification) {
                Log::warning('WhatsApp notifications disabled for company: ' . $company->id);
                return false;
            }

            if (!$setting->whatsapp_api_url || !$setting->whatsapp_api_key || !$setting->whatsapp_phone_number_id) {
                Log::warning('WhatsApp not properly configured for company: ' . $company->id);
                return false;
            }

            // Get recipient phone number
            $recipientPhone = null;

            if (isset($record->worker) && $record->worker && $record->worker->phone_number) {
                $recipientPhone = $record->worker->phone_number;
            } elseif ($company->phone) {
                $recipientPhone = $company->phone;
            }

            if (!$recipientPhone) {
                Log::warning('No phone number found for WhatsApp notification: ' . $record->id);
                return false;
            }

            // Calculate days left
            $daysLeft = 'manual';
            if ($expiryDate) {
                $days = Carbon::today()->diffInDays(Carbon::parse($expiryDate), false);
                $daysLeft = $days > 0 ? (string)$days : 'scaduto';
            }

            // Build template parameters
            $templateParams = $whatsappService->buildTemplateParams($record, $module, $daysLeft);

            // Send WhatsApp message
            $templateName = $setting->whatsapp_template_name ?? 'expiry_reminder';
            $sent = $whatsappService->sendMessage(
                $setting->whatsapp_api_url,
                $setting->whatsapp_api_key,
                $setting->whatsapp_phone_number_id,
                $recipientPhone,
                $templateName,
                $templateParams
            );

            if ($sent) {
                Log::info('Dashboard WhatsApp sent', [
                    'module' => $module,
                    'record_id' => $record->id,
                    'recipient' => $recipientPhone
                ]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('sendWhatsAppNotification error: ' . $e->getMessage());
            return false;
        }
    }
}
