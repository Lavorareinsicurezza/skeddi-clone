<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyCourseType;
use App\Models\CourseType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
        $companyId = session('selectedCompanyId');

        $company = Company::find($companyId);
        $courseTypeAid = CompanyCourseType::select('id', 'name', 'company_id', 'course_type_id')
            ->whereHas('courseType', function ($query) {
                $query->where('course_name', 'Primo soccorso')
                    ->orWhere('course_name', 'LIKE', '%Antincendio%');
            })->where('company_id', $companyId)->with(['trainingPlanRecords' => function ($query) {
                $query->select('id',  'worker_id', 'company_course_type_id')->with(['worker' => function ($query) {
                    $query->select('id', 'first_name', 'surname');
                }]);
            }])->first();
        $courseTypeFireFighter = CompanyCourseType::select('id', 'name', 'company_id', 'course_type_id')
            ->whereHas('courseType', function ($query) {
                $query->where('course_name', 'LIKE', '%Antincendio%');
            })->where('company_id', $companyId)->with(['trainingPlanRecords' => function ($query) {
                $query->select('id',  'worker_id', 'company_course_type_id')->with(['worker' => function ($query) {
                    $query->select('id', 'first_name', 'surname');
                }]);
            }])->get();
        // return $courseTypeFireFighter;
        // return $courseTypeAid;
        return view('company.chart.index', compact('company', 'courseTypeAid', 'courseTypeFireFighter'));
    }

    public function detail()
    {
        $companyId = session('selectedCompanyId');

        $company = Company::find($companyId);
        $courseTypeAid = CompanyCourseType::select('id', 'name', 'company_id', 'course_type_id')
            ->whereHas('courseType', function ($query) {
                $query->where('course_name', 'Primo soccorso')
                    ->orWhere('course_name', 'LIKE', '%Antincendio%');
            })->where('company_id', $companyId)->with(['trainingPlanRecords' => function ($query) {
                $query->select('id',  'worker_id', 'company_course_type_id')->with(['worker' => function ($query) {
                    $query->select('id', 'first_name', 'surname');
                }]);
            }])->first();
        $courseTypeFireFighter = CompanyCourseType::select('id', 'name', 'company_id', 'course_type_id')
            ->whereHas('courseType', function ($query) {
                $query->where('course_name', 'LIKE', '%Antincendio%');
            })->where('company_id', $companyId)->with(['trainingPlanRecords' => function ($query) {
                $query->select('id',  'worker_id', 'company_course_type_id')->with(['worker' => function ($query) {
                    $query->select('id', 'first_name', 'surname');
                }]);
            }])->get();
        // return $courseTypeFireFighter;
        // return $courseTypeAid;
        return view('company.chart.detail', compact('company', 'courseTypeAid', 'courseTypeFireFighter'));
    }

    public function downloadPdf()
{
    $companyId = session('selectedCompanyId');
    $company = Company::find($companyId);

    $courseTypeAid = CompanyCourseType::select('id', 'name', 'company_id', 'course_type_id')
        ->whereHas('courseType', function ($query) {
            $query->where('course_name', 'Primo soccorso')
                ->orWhere('course_name', 'LIKE', '%Antincendio%');
        })
        ->where('company_id', $companyId)
        ->with(['trainingPlanRecords' => function ($query) {
            $query->select('id', 'worker_id', 'company_course_type_id')
                ->with(['worker:id,first_name,surname']);
        }])
        ->first();

    $firefighterWorkers = CompanyCourseType::select('id', 'name', 'company_id', 'course_type_id')
        ->whereHas('courseType', function ($query) {
            $query->where('course_name', 'LIKE', '%Antincendio%');
        })
        ->where('company_id', $companyId)
        ->with(['trainingPlanRecords' => function ($query) {
            $query->select('id', 'worker_id', 'company_course_type_id')
                ->with(['worker:id,first_name,surname']);
        }])
        ->get();

    // PDF blade render
    $pdf = Pdf::loadView('company.chart.pdf', compact('company', 'courseTypeAid', 'firefighterWorkers'))
        ->setPaper('A4', 'portrait');

    return $pdf->download('organizational_chart.pdf');
}

}
