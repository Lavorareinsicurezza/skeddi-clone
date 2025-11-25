<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class CompaniesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Company([
            'company_id' => Auth::user()->company_id,
            'name' => $row['name'],
            'vat_number' => $row['vat_number'] ?? null,
            'tax_code' => $row['tax_code'] ?? null,
            'ateco' => $row['ateco'] ?? null,
            'sdi' => $row['sdi'] ?? null,
            'registered_office' => $row['registered_office'] ?? null,
            'operating_office' => $row['operating_office'] ?? null,
            'main_email' => $row['main_email'] ?? null,
            'pec_email' => $row['pec_email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'phone_2' => $row['phone_2'] ?? null,
            'company_contact_person' => $row['contact_person'] ?? null,
            'employer' => $row['employer'] ?? null,
            'head_of_prevention' => $row['head_of_prevention'] ?? null,
            'workers_safety_representative' => $row['workers_safety_representative'] ?? null,
            'company_doctor' => $row['company_doctor'] ?? null,
            'workplace_safety_risk' => $row['workplace_safety_risk'] ?? null,
            'subject_to_cpi' => $this->convertToBoolean($row['subject_to_cpi'] ?? 'No'),
            'rischio_antincendio' => $row['rischio_antincendio'] ?? null,
            'accountant_name' => $row['accountant_name'] ?? null,
            'accountant_phone' => $row['accountant_phone'] ?? null,
            'accountant_email' => $row['accountant_email'] ?? null,
            'labor_consultant_name' => $row['labor_consultant_name'] ?? null,
            'labor_consultant_phone' => $row['labor_consultant_phone'] ?? null,
            'labor_consultant_email' => $row['labor_consultant_email'] ?? null,
            'notes' => $row['notes'] ?? null,
            'send_deadline_notification' => $this->convertToBoolean($row['send_deadline_notification'] ?? 'No'),
            'freeze_company' => $this->convertToBoolean($row['freeze_company'] ?? 'No'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'vat_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('companies', 'vat_number')->whereNotNull('vat_number'),
            ],
            'tax_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('companies', 'tax_code')->whereNotNull('tax_code'),
            ],
            'main_email' => [
                'nullable',
                'email',
                Rule::unique('companies', 'main_email')->whereNotNull('main_email'),
            ],
            'pec_email' => [
                'nullable',
                'email',
                Rule::unique('companies', 'pec_email')->whereNotNull('pec_email'),
            ],
            'accountant_email' => 'nullable|email',
            'labor_consultant_email' => 'nullable|email',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'vat_number.unique' => 'The VAT number :input already exists in the database.',
            'tax_code.unique' => 'The tax code :input already exists in the database.',
            'main_email.unique' => 'The main email :input already exists in the database.',
            'pec_email.unique' => 'The PEC email :input already exists in the database.',
        ];
    }

    /**
     * Convert string to boolean
     *
     * @param string $value
     * @return bool
     */
    private function convertToBoolean($value): bool
    {
        $value = strtolower(trim($value));
        return in_array($value, ['yes', '1', 'true', 'si', 'sì']);
    }
}
