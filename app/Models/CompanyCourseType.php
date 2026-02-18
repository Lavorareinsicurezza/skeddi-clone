<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCourseType extends Model
{
    protected $fillable = [
        'company_id',
        'course_type_id',
        'name',
        'validity_years',
        'generic_column_name',
        'expiration_date',
        'expiration_column_name',
        'is_generic',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'is_generic' => 'boolean',
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

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function trainingPlanRecords()
    {
        return $this->hasMany(TrainingPlanRecord::class, 'company_course_type_id');
    }
}
