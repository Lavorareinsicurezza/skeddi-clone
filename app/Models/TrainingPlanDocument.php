<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingPlanDocument extends Model
{
    protected $fillable = [
        'company_id',
        'worker_id',
        'company_course_type_id',
        'training_plan_record_id',
        'file_path',
        'file_name',
        'note',
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

    public function trainingPlanRecord()
    {
        return $this->belongsTo(TrainingPlanRecord::class);
    }
}
