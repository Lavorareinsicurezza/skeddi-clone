<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $companies = Company::all();

        return view('welcome', [
            'currentCompany' => $company,
            'companies' => $companies,
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
