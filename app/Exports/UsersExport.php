<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with('company')
            ->company()
            ->where('role', '!=', 'superadmin')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nome',
            'Email',
            'Ruolo',
            'Azienda',
            'Funzioni',
            'Aziende Visibili',
            'Funzioni Admin',
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        // Convert arrays to comma-separated strings for better readability
        $functions = is_array($user->functions) ? implode(', ', $user->functions) : '';

        // Convert company IDs to company names
        $visibleCompanyNames = '';
        if (is_array($user->visible_company_ids) && count($user->visible_company_ids) > 0) {
            $companies = Company::whereIn('id', $user->visible_company_ids)->pluck('name')->toArray();
            $visibleCompanyNames = implode(', ', $companies);
        }

        $adminFunctions = is_array($user->admin_functions) ? implode(', ', $user->admin_functions) : '';

        return [
            $user->name,
            $user->email,
            $user->role,
            $user->company->name ?? '',
            $functions,
            $visibleCompanyNames,
            $adminFunctions,
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
