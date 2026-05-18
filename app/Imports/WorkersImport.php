<?php

namespace App\Imports;

use App\Models\Worker;
use App\Models\OperatingLocation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WorkersImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $companyId;

    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    public function model(array $row): Worker
    {
        $operatingLocationId = null;
        if (!empty($row['sede_operativa'])) {
            $location = OperatingLocation::where('company_id', $this->companyId)
                ->where('name', trim($row['sede_operativa']))
                ->first();
            $operatingLocationId = $location?->id;
        }

        $isInactive = !empty($row['non_attivo'])
            && in_array(strtolower(trim($row['non_attivo'])), ['yes', 'si', '1', 'true']);

        $safetyRisk = !empty($row['rischio_sicurezza'])
            && in_array(strtolower(trim($row['rischio_sicurezza'])), ['yes', 'si', '1', 'true']);

        return new Worker([
            'company_id'                  => $this->companyId,
            'operating_location_id'       => $operatingLocationId,
            'first_name'                  => $row['nome'],
            'surname'                     => $row['cognome'],
            'job_title'                   => $row['mansione'] ?? null,
            'is_active'                   => !$isInactive,
            'additional_information'      => $row['informazioni_aggiuntive'] ?? null,
            'worker_documentation'        => $row['documentazione_lavoratore'] ?? null,
            'movement_history'            => $row['storico_spostamenti'] ?? null,
            'training_experience'         => $row['esperienza_formativa'] ?? null,
            'workplace_safety_risk'       => $safetyRisk,
            'workplace_safety_risk_note'  => $row['note_rischio_sicurezza'] ?? null,
            'medical_visits'              => $row['visite_mediche'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'cognome' => 'required|string|max:255',
            'nome'    => 'required|string|max:255',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'cognome.required' => 'The Cognome (surname) field is required.',
            'nome.required'    => 'The Nome (first name) field is required.',
        ];
    }
}
