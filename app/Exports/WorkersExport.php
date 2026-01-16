<?php

namespace App\Exports;

use App\Models\Worker;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Worker::query()->company()->with('operatingLocation')->orderBy('surname')->get();
    }

    public function headings(): array
    {
        return [
            'Surname',
            'First Name',
            'Operating Location',
            'Job Title',
            'Workplace Safety Risk',
            'Is Active',
            'Additional Information',
        ];
    }

    public function map($worker): array
    {
        return [
            $worker->surname,
            $worker->first_name,
            $worker->operatingLocation?->name,
            $worker->job_title,
            $worker->workplace_safety_risk ? 'Yes' : 'No',
            $worker->is_active ? 'Yes' : 'No',
            $worker->additional_information,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

