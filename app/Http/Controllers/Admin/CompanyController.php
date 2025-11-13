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
            'tax_code' => 'required|string|max:255',
            'ateco' => 'required|string|max:255',
            'sdi' => 'nullable|string|max:255',
            'registered_office' => 'required|string',
            'operating_office' => 'nullable|string',
            'main_email' => 'required|email|max:255',
            'pec_email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'company_contact_person' => 'required|string|max:255',
            'employer' => 'required|string|max:255',
            'head_of_prevention' => 'required|string|max:255',
            'workers_safety_representative' => 'required|string|max:255',
            'company_doctor' => 'required|string|max:255',
            'workplace_safety_risk' => 'nullable|boolean',
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
            'contacts' => 'required|array|min:1',
            'contacts.*' => 'string|email',
        ], [
            'company_name.required' => 'Company name is required.',
            'vat_number.required' => 'VAT number is required.',
            'vat_number.unique' => 'This VAT number is already registered.',
            'tax_code.required' => 'Tax code is required.',
            'ateco.required' => 'ATECO code is required.',
            'registered_office.required' => 'Registered office address is required.',
            'main_email.required' => 'Main email is required.',
            'main_email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Phone number is required.',
            'company_contact_person.required' => 'Company contact person is required.',
            'employer.required' => 'Employer name is required.',
            'head_of_prevention.required' => 'Head of Prevention and Protection Service is required.',
            'workers_safety_representative.required' => 'Workers\' Safety Representative is required.',
            'company_doctor.required' => 'Company doctor is required.',
            'contacts.required' => 'Please select at least one contact.',
            'contacts.min' => 'Please select at least one contact.',
            'pec_email.email' => 'Please enter a valid PEC email address.',
        ]);

        // Map company_name to name field
        $data = $validated;
        $data['name'] = $validated['company_name'];
        unset($data['company_name']);

        // Convert checkboxes to boolean (unchecked checkboxes don't submit, so we check for their presence)
        $data['workplace_safety_risk'] = $request->has('workplace_safety_risk');
        $data['subject_to_cpi'] = $request->has('subject_to_cpi');
        $data['send_deadline_notification'] = $request->has('send_deadline_notification');
        $data['freeze_company'] = $request->has('freeze_company');

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
            'tax_code' => 'required|string|max:255',
            'ateco' => 'required|string|max:255',
            'sdi' => 'nullable|string|max:255',
            'registered_office' => 'required|string',
            'operating_office' => 'nullable|string',
            'main_email' => 'required|email|max:255',
            'pec_email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'company_contact_person' => 'required|string|max:255',
            'employer' => 'required|string|max:255',
            'head_of_prevention' => 'required|string|max:255',
            'workers_safety_representative' => 'required|string|max:255',
            'company_doctor' => 'required|string|max:255',
            'workplace_safety_risk' => 'nullable|boolean',
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
            'contacts' => 'required|array|min:1',
            'contacts.*' => 'string|email',
        ], [
            'company_name.required' => 'Company name is required.',
            'vat_number.required' => 'VAT number is required.',
            'vat_number.unique' => 'This VAT number is already registered.',
            'tax_code.required' => 'Tax code is required.',
            'ateco.required' => 'ATECO code is required.',
            'registered_office.required' => 'Registered office address is required.',
            'main_email.required' => 'Main email is required.',
            'main_email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Phone number is required.',
            'company_contact_person.required' => 'Company contact person is required.',
            'employer.required' => 'Employer name is required.',
            'head_of_prevention.required' => 'Head of Prevention and Protection Service is required.',
            'workers_safety_representative.required' => 'Workers\' Safety Representative is required.',
            'company_doctor.required' => 'Company doctor is required.',
            'contacts.required' => 'Please select at least one contact.',
            'contacts.min' => 'Please select at least one contact.',
            'pec_email.email' => 'Please enter a valid PEC email address.',
        ]);

        // Map company_name to name field
        $data = $validated;
        $data['name'] = $validated['company_name'];
        unset($data['company_name']);

        // Convert checkboxes to boolean (unchecked checkboxes don't submit, so we check for their presence)
        $data['workplace_safety_risk'] = $request->has('workplace_safety_risk');
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
