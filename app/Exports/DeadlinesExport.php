<?php

namespace App\Exports;

use App\Models\TrainingPlanRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeadlinesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private ?int $operatingLocationId;

    public function __construct(?int $operatingLocationId = null)
    {
        $this->operatingLocationId = $operatingLocationId;
    }

    public function collection()
    {
        $query = TrainingPlanRecord::with('companyCourseType', 'worker.operatingLocation', 'company')->company()
            ->when($this->operatingLocationId, function ($q) {
                $q->whereHas('worker', function ($qw) {
                    $qw->where('operating_location_id', $this->operatingLocationId);
                });
            });

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Ragione Sociale',
            'Nome Corso',
            'Nome Dipendente',
            'Sede Operativa',
            'Data Formazione',
            'Data Scadenza',
            'Da Programmare',
        ];
    }

    public function map($record): array
    {
        return [
            $record->company?->name,
            $record->companyCourseType?->name,
            $record->worker?->surname,
            $record->worker?->operatingLocation?->name,
            optional($record->training_date)->format('d/m/Y'),
            optional($record->expiration_date)->format('d/m/Y'),
            $record->to_be_scheduled ? 'Yes' : 'No',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

