<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\CompaniesExport;
use App\Exports\CompaniesTemplateExport;
use App\Imports\CompaniesImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $companies = Company::where('company_id', $companyId)->paginate(20);

        return view('admin.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userEmails = User::select('email')->company()->where('role', '!=', 'superadmin')->get();
        // return $userEmails;
        return view('admin.company.create', compact('userEmails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255|unique:companies,vat_number',
            'contacts' => 'required|array|min:1',
            'contacts.*' => 'string|email',
            'tax_code' => 'nullable|string|max:255',
            'ateco' => 'nullable|string|max:255',
            'sdi' => 'nullable|string|max:255',
            'registered_office' => 'nullable|string',
            'operating_office' => 'nullable|string',
            'main_email' => 'nullable|email|max:255',
            'pec_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'company_contact_person' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'head_of_prevention' => 'nullable|string|max:255',
            'workers_safety_representative' => 'nullable|string|max:255',
            'company_doctor' => 'nullable|string|max:255',
            'workplace_safety_risk' => 'nullable|string|max:255',
            'subject_to_cpi' => 'nullable|boolean',
            'rischio_antincendio' => 'nullable|string|max:255',
            'accountant_name' => 'nullable|string|max:255',
            'accountant_phone' => 'nullable|string|max:20',
            'accountant_email' => 'nullable|email|max:255',
            'labor_consultant_name' => 'nullable|string|max:255',
            'labor_consultant_phone' => 'nullable|string|max:20',
            'labor_consultant_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'send_deadline_notification' => 'nullable|boolean',
            'freeze_company' => 'nullable|boolean',
        ], [
            'company_name.required' => 'Company name is required.',
            'vat_number.required' => 'VAT number is required.',
            'vat_number.unique' => 'This VAT number is already registered.',
            'contacts.required' => 'Please select at least one contact.',
            'contacts.min' => 'Please select at least one contact.'

        ]);

        // Map company_name to name field
        $data = $validated;
        $data['name'] = $validated['company_name'];
        unset($data['company_name']);

        // Convert checkboxes to boolean (unchecked checkboxes don't submit, so we check for their presence)
        $data['workplace_safety_risk'] = $request->workplace_safety_risk ?? null;
        $data['subject_to_cpi'] = $request->has('subject_to_cpi') ?? null;
        $data['send_deadline_notification'] = $request->has('send_deadline_notification') ?? null;
        $data['freeze_company'] = $request->has('freeze_company') ?? null;

        // Add company_id from authenticated user
        $data['company_id'] = Auth::user()->company_id;

        Company::create($data);

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        return view('admin.company.show', compact('company'));
    }

    /**
     * Display the selected company details.
     */
    public function showSelectedCompany()
    {
        $companyId = session('selectedCompanyId');

        if (!$companyId) {
            return redirect()->route('admin.dashboard')->with('error', 'Please select a company first.');
        }

        $company = Company::findOrFail($companyId);
        return view('admin.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        $userEmails = User::select('email')->company()->where('role', '!=', 'superadmin')->get();
        $userEmails = $userEmails->map(function ($u) {
            return $u->email;
        });
        return view('admin.company.edit', compact('company', 'userEmails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255|unique:companies,vat_number,' . $id,
            'contacts' => 'required|array|min:1',
            'contacts.*' => 'string|email'
        ], [
            'company_name.required' => 'Company name is required.',
            'vat_number.required' => 'VAT number is required.',
            'vat_number.unique' => 'This VAT number is already registered.',
            'contacts.required' => 'Please select at least one contact.',
            'contacts.min' => 'Please select at least one contact.'
        ]);

        // Map company_name to name field
        $data = $validated;
        $data['name'] = $validated['company_name'];
        unset($data['company_name']);

        // Convert checkboxes to boolean (unchecked checkboxes don't submit, so we check for their presence)
        $data['workplace_safety_risk'] = $request->workplace_safety_risk;
        $data['subject_to_cpi'] = $request->has('subject_to_cpi');
        $data['send_deadline_notification'] = $request->has('send_deadline_notification');
        $data['freeze_company'] = $request->has('freeze_company');

        $company->update($data);

        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Export companies to Excel
     */
    public function export()
    {
        return Excel::download(new CompaniesExport, 'companies-' . date('Y-m-d-His') . '.xlsx');
    }

    /**
     * Import companies from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new CompaniesImport, $request->file('file'));

            return redirect()->route('admin.companies.index')
                ->with('success', 'Companies imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $row = $failure->row();
                $attribute = $failure->attribute();
                $errors = $failure->errors();
                $values = $failure->values();

                foreach ($errors as $error) {
                    $errorMessages[] = "Row {$row}: {$error}";
                }
            }

            $errorMessage = implode('<br>', $errorMessages);

            return redirect()->route('admin.companies.index')
                ->with('error', 'Validation errors occurred during import:<br>' . $errorMessage);
        } catch (\Exception $e) {
            return redirect()->route('admin.companies.index')
                ->with('error', 'Error importing companies: ' . $e->getMessage());
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        return Excel::download(new CompaniesTemplateExport, 'companies-import-template.xlsx');
    }
}
