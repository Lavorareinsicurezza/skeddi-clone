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
            'company_id'                    => Auth::user()->company_id,
            'name'                          => $row['ragione_sociale'],
            'vat_number'                    => $row['partita_iva'] ?? null,
            'tax_code'                      => $row['codice_fiscale'] ?? null,
            'ateco'                         => $row['ateco'] ?? null,
            'sdi'                           => $row['codice_sdi'] ?? null,
            'registered_office'             => $row['sede_legale'] ?? null,
            'operating_office'              => $row['sede_operativa'] ?? null,
            'main_email'                    => $row['email_principale'] ?? null,
            'pec_email'                     => $row['pec'] ?? null,
            'phone'                         => $row['telefono'] ?? null,
            'phone_2'                       => $row['telefono_2'] ?? null,
            'company_contact_person'        => $row['referente'] ?? null,
            'employer'                      => $row['datore_di_lavoro'] ?? null,
            'head_of_prevention'            => $row['rspp'] ?? null,
            'workers_safety_representative' => $row['rls'] ?? null,
            'company_doctor'                => $row['medico_competente'] ?? null,
            'workplace_safety_risk'         => $row['rischio_sicurezza'] ?? null,
            'subject_to_cpi'                => $this->convertToBoolean($row['soggetto_a_cpi'] ?? 'No'),
            'rischio_antincendio'           => $row['rischio_antincendio'] ?? null,
            'accountant_name'               => $row['nome_commercialista'] ?? null,
            'accountant_phone'              => $row['telefono_commercialista'] ?? null,
            'accountant_email'              => $row['email_commercialista'] ?? null,
            'labor_consultant_name'         => $row['nome_consulente_del_lavoro'] ?? null,
            'labor_consultant_phone'        => $row['telefono_consulente_del_lavoro'] ?? null,
            'labor_consultant_email'        => $row['email_consulente_del_lavoro'] ?? null,
            'notes'                         => $row['note'] ?? null,
            'send_deadline_notification'    => $this->convertToBoolean($row['invia_notifica_scadenze'] ?? 'No'),
            'freeze_company'                => $this->convertToBoolean($row['congela_azienda'] ?? 'No'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'ragione_sociale'        => 'required|string|max:255',
            'partita_iva'            => [
                'nullable',
                'numeric',
                Rule::unique('companies', 'vat_number')->whereNotNull('vat_number'),
            ],
            'codice_fiscale'         => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('companies', 'tax_code')->whereNotNull('tax_code'),
            ],
            'email_principale'       => [
                'nullable',
                'email',
                Rule::unique('companies', 'main_email')->whereNotNull('main_email'),
            ],
            'pec'                    => [
                'nullable',
                'email',
                Rule::unique('companies', 'pec_email')->whereNotNull('pec_email'),
            ],
            'email_commercialista'   => 'nullable|email',
            'email_consulente_del_lavoro' => 'nullable|email',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'partita_iva.unique'      => 'The VAT number :input already exists in the database.',
            'codice_fiscale.unique'   => 'The tax code :input already exists in the database.',
            'email_principale.unique' => 'The main email :input already exists in the database.',
            'pec.unique'              => 'The PEC email :input already exists in the database.',
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
