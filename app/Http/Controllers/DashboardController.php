<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\TrainingPlanRecord;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // if($request->scheduled) return $request->all();
        $user = Auth::user();
        $company = $user->company;

        // Build query with filters
        $query = TrainingPlanRecord::with('companyCourseType', 'worker', 'company');

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('expiration_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expiration_date', '<=', $request->to_date);
        }

        // Filter by scheduled
        if ($request->filled('scheduled')) {
            $query->where('to_be_scheduled', $request->scheduled == 'true'? 1:0);
        }

        $traningPlans = $query->latest()->get();

        return view('welcome', [
            'currentCompany' => $company,
            'trainingPlans' => $traningPlans,
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

        // Build query with filters
        $query = TrainingPlanRecord::with('companyCourseType', 'worker', 'company')->company();

        // // Filter by date range
        // if ($request->filled('from_date')) {
        //     $query->whereDate('expiration_date', '>=', $request->from_date);
        // }

        // if ($request->filled('to_date')) {
        //     $query->whereDate('expiration_date', '<=', $request->to_date);
        // }

        // // Filter by scheduled
        // if ($request->filled('scheduled')) {
        //     $query->where('to_be_scheduled', $request->scheduled == 'true'? 1:0);
        // }

        $trainingPlans = $query->latest()->get();

        return view('company.deadlines.index', [
            'currentCompany' => $company,
            'trainingPlans' => $trainingPlans,
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
        $companies = Company::select('id', 'name', 'phone')->where('company_id', $user->company_id)->get();

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
