<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompaniesTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return sample row
        return [
            [
                'Company ABC',
                '12345678901',
                'ABC12345678',
                '12.34.56',
                'SDI123',
                'Via Roma 123, Milano',
                'Via Milano 456, Roma',
                'info@companyabc.com',
                'pec@companyabc.com',
                '+39 02 1234567',
                '+39 02 7654321',
                'Mario Rossi',
                'Giovanni Bianchi',
                'Luca Verdi',
                'Paolo Neri',
                'Dr. Andrea Rossi',
                'Yes',
                'No',
                'Rischio Medio',
                'Studio Commercialista',
                '+39 02 1111111',
                'commercialista@email.com',
                'Consulente Del Lavoro',
                '+39 02 2222222',
                'consulente@email.com',
                'Sample notes here',
                'Yes',
                'No',
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Ragione Sociale',
            'Partita IVA',
            'Codice Fiscale',
            'ATECO',
            'Codice SDI',
            'Sede Legale',
            'Sede Operativa',
            'Email Principale',
            'PEC',
            'Telefono',
            'Telefono 2',
            'Referente',
            'Datore di Lavoro',
            'RSPP',
            'RLS',
            'Medico Competente',
            'Rischio Sicurezza',
            'Soggetto a CPI',
            'Rischio Antincendio',
            'Nome Commercialista',
            'Telefono Commercialista',
            'Email Commercialista',
            'Nome Consulente del Lavoro',
            'Telefono Consulente del Lavoro',
            'Email Consulente del Lavoro',
            'Note',
            'Invia Notifica Scadenze',
            'Congela Azienda',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0C3183']]],
            2 => ['font' => ['italic' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E8F0FE']]],
        ];
    }
}
