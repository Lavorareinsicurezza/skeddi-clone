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
    private $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Tipo',
            'Lavoratore',
            'Sede',
            'Data di Scadenza',
        ];
    }

    public function map($record): array
    {
        return [
            $record->name,
            $record->deadline_type,
            $record->employee_name,
            $record->location_name,
            $record->expiry_date,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

