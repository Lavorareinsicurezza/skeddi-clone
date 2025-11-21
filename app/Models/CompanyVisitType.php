<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyVisitType extends Model
{
    protected $fillable = [
        'company_id',
        'visit_type_id',
        'name',
        'specific_name',
        'expiry_date',
        'notes'
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

    public function visitType()
    {
        return $this->belongsTo(VisitType::class);
    }
}
