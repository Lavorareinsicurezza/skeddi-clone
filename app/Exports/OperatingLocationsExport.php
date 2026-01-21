<?php

namespace App\Exports;

use App\Models\OperatingLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OperatingLocationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return OperatingLocation::query()->company()->with('company')->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Azienda',
            'Indirizzo',
            'Nome Referente Sede',
            'Telefono Referente Sede',
            'Email Referente Sede',
            'Stato',
            'Data Creazione',
        ];
    }

    public function map($location): array
    {
        return [
            $location->name,
            $location->company?->name,
            $location->full_address,
            $location->site_contact_name,
            $location->site_contact_phone,
            $location->site_contact_email,
            $location->is_active ? 'Active' : 'Inactive',
            $location->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

