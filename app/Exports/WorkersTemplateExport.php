<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class WorkersTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'Rossi',
                'Mario',
                'Sede Milano',
                'Operaio',
                'No',
                '',
                '',
                '',
                '',
                'No',
                '',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Cognome',
            'Nome',
            'Sede Operativa',
            'Mansione',
            'Non Attivo (Yes/No)',
            'Informazioni Aggiuntive',
            'Documentazione Lavoratore',
            'Storico Spostamenti',
            'Esperienza Formativa',
            'Rischio Sicurezza (Yes/No)',
            'Note Rischio Sicurezza',
            'Visite Mediche',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0C3183'],
            ],
        ]);

        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}
