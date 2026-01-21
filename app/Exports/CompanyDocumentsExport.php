<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompanyDocumentsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Document::query()->company()->with('documentType');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome Documento',
            'Tipo Documento',
            'Nota Scadenzario',
            'Data Scadenza',
            'Da Scadenzare',
            'Note',
            'Data Creazione',
        ];
    }

    public function map($document): array
    {
        return [
            $document->id,
            $document->name,
            $document->documentType?->name ?? 'N/A',
            $document->scheduling_note,
            $document->expiration_date,
            $document->to_schedule ? 'Yes' : 'No',
            $document->notes,
            $document->created_at->format('d/m/Y H:i'),
        ];
    }
}
