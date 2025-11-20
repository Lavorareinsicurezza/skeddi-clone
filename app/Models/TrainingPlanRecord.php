<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingPlanRecord extends Model
{
    protected $fillable = [
        'company_id',
        'worker_id',
        'company_course_type_id',
        'training_date',
        'expiration_date',
        'to_be_scheduled',
    ];

    protected $casts = [
        'training_date' => 'date',
        'expiration_date' => 'date',
        'to_be_scheduled' => 'boolean',
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

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function companyCourseType()
    {
        return $this->belongsTo(CompanyCourseType::class);
    }
}
