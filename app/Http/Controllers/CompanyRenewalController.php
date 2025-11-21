<?php

namespace App\Http\Controllers;

use App\Models\CourseRenewalLog;
use Illuminate\Http\Request;

class CompanyRenewalController extends Controller
{
    public function index()
    {
        $companyId = session('selectedCompanyId'); // or however you get the current company

    $renewals = CourseRenewalLog::with(['worker', 'companyCourseType', 'renewedBy'])
        ->where('company_id', $companyId)
        ->latest('renewal_operation_date')  // most recent first
        ->paginate(50); // or use ->get() if you want all

        return view('company.renewal.index', compact('renewals'));
    }
}
