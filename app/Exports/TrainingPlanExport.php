<?php

namespace App\Exports;

use App\Models\TrainingPlanRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrainingPlanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return TrainingPlanRecord::with('companyCourseType', 'worker.operatingLocation', 'company')->company()->latest()->get();
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
            $record->worker?->first_name .' '. $record->worker?->surname,
            $record->worker?->operatingLocation?->name,
            optional($record->training_date)->format('Y-m-d'),
            optional($record->expiration_date)->format('Y-m-d'),
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

