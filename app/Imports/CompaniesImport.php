<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompaniesImport implements ToModel, WithHeadingRow, WithValidation, WithMapping
{
    /**
     * @param array $row
     * @return array
     */
    public function map($row): array
    {
        $row = array_map(fn($value) => is_string($value) ? trim($value) : $value, $row);

        if (!empty($row['partita_iva'])) {
            $row['partita_iva'] = preg_replace('/\D/', '', $row['partita_iva']);
        }

        if (!empty($row['email_principale']) && preg_match('/[,;]/', $row['email_principale'])) {
            $row['email_principale'] = trim(preg_split('/[,;]/', $row['email_principale'])[0]);
        }

        return $row;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $vatNumber  = $row['partita_iva'] ?? null;
        $taxCode    = $row['codice_fiscale'] ?? null;
        $mainEmail  = $row['email_principale'] ?? null;
        $pecEmail   = $row['pec'] ?? null;

        $duplicate = Company::query()
            ->where(function ($q) use ($vatNumber, $taxCode, $mainEmail, $pecEmail) {
                if ($vatNumber)  $q->orWhere('vat_number', $vatNumber);
                if ($taxCode)    $q->orWhere('tax_code', $taxCode);
                if ($mainEmail)  $q->orWhere('main_email', $mainEmail);
                if ($pecEmail)   $q->orWhere('pec_email', $pecEmail);
            })
            ->exists();

        if ($duplicate) {
            return null;
        }

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
            'ragione_sociale'             => 'required|string|max:255',
            'partita_iva'                 => 'nullable|numeric',
            'codice_fiscale'               => 'nullable|string|max:255',
            'email_principale'            => 'nullable|email',
            'pec'                         => 'nullable|email',
            'email_commercialista'        => 'nullable|email',
            'email_consulente_del_lavoro' => 'nullable|email',
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
