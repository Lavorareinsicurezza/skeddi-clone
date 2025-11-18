<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    protected $fillable = [
        'company_id',
        'document_type_id',
        'name',
        'scheduling_note',
        'expiration_date',
        'to_schedule',
        'notes',
    ];

    public function scopeCompany($query)
    {
        $companyId = session('selectedCompanyId');
        return $query->where('company_id', $companyId);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
