<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = [
        'company_id',
        'first_name',
        'surname',
        'job_title',
        'department',
        'workplace_safety_risk',
        'is_active',
        'additional_information',
        'worker_documentation',
        'ppe',
        'movement_history',
        'training_experience',
        'medical_visits',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'workplace_safety_risk' => 'boolean',
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
}
