<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompaniesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private ?int $companyId = null;

    public function __construct(?int $companyId = null)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Company::company();
        if ($this->companyId) {
            $query->where('id', $this->companyId);
        }
        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'VAT Number',
            'Tax Code',
            'ATECO',
            'SDI',
            'Registered Office',
            'Main Email',
            'PEC Email',
            'Phone',
            'Phone 2',
            'Contact Person',
            'Employer',
            'Head of Prevention',
            'Workers Safety Representative',
            'Company Doctor',
            'Workplace Safety Risk',
            'Subject to CPI',
            'Rischio Antincendio',
            'Accountant Name',
            'Accountant Phone',
            'Accountant Email',
            'Labor Consultant Name',
            'Labor Consultant Phone',
            'Labor Consultant Email',
            'Notes',
            'Send Deadline Notification',
            'Freeze Company',
            'Created At',
        ];
    }

    /**
     * @param Company $company
     * @return array
     */
    public function map($company): array
    {
        return [
            $company->id,
            $company->name,
            $company->vat_number,
            $company->tax_code,
            $company->ateco,
            $company->sdi,
            $company->registered_office,
            $company->main_email,
            $company->pec_email,
            $company->phone,
            $company->phone_2,
            $company->company_contact_person,
            $company->employer,
            $company->head_of_prevention,
            $company->workers_safety_representative,
            $company->company_doctor,
            str_replace('_', ' ', $company->workplace_safety_risk),
            $company->subject_to_cpi ? 'Yes' : 'No',
            $company->rischio_antincendio,
            $company->accountant_name,
            $company->accountant_phone,
            $company->accountant_email,
            $company->labor_consultant_name,
            $company->labor_consultant_phone,
            $company->labor_consultant_email,
            $company->notes,
            $company->send_deadline_notification ? 'Yes' : 'No',
            $company->freeze_company ? 'Yes' : 'No',
            $company->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
