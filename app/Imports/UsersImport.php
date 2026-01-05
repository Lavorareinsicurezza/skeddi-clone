<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Convert comma-separated strings to arrays
        $functions = isset($row['functions_comma_separated']) && $row['functions_comma_separated']
            ? array_map('trim', explode(',', $row['functions_comma_separated']))
            : [];

        // Convert company names to company IDs
        $visibleCompanyIds = [];
        if (isset($row['visible_company_names_comma_separated']) && $row['visible_company_names_comma_separated']) {
            $companyNames = array_map('trim', explode(',', $row['visible_company_names_comma_separated']));

            foreach ($companyNames as $companyName) {
                $company = Company::where('company_id', Auth::user()->company_id)
                    ->where('name', $companyName)
                    ->first();

                if ($company) {
                    $visibleCompanyIds[] = $company->id;
                }
            }
        }

        $adminFunctions = isset($row['admin_functions_comma_separated']) && $row['admin_functions_comma_separated']
            ? array_map('trim', explode(',', $row['admin_functions_comma_separated']))
            : [];

        return new User([
            'company_id' => Auth::user()->company_id,
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'] ?? 'user',
            'password' => Hash::make(bin2hex(random_bytes(8))), // Random password
            'functions' => $functions,
            'visible_company_ids' => $visibleCompanyIds,
            'admin_functions' => $adminFunctions,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'role' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email :input already exists in the database.',
        ];
    }
}
