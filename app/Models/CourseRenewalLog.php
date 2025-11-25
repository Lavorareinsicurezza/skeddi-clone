<?php
// app/Models/CourseRenewalLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseRenewalLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'course_renewal_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'item_id',
        'worker_id',
        'company_course_type_id',
        'deadline_type',
        'renewed_by',
        'managed_by',
        'subject',
        'previous_expiry_date',
        'course_update_date',
        'new_expiry_date',
        'renewal_operation_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'previous_expiry_date'   => 'date:Y-m-d',
        'course_update_date'     => 'date:Y-m-d',
        'new_expiry_date'        => 'date:Y-m-d',
        'renewal_operation_date'=> 'date:Y-m-d',
    ];

    /**
     * Get the company that owns the renewal log.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the training plan record associated with this renewal.
     */
    public function trainingPlanRecord(): BelongsTo
    {
        return $this->belongsTo(TrainingPlanRecord::class, 'training_plan_record_id');
    }

    /**
     * Get the worker who received the training.
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Get the company-specific course type.
     */
    public function companyCourseType(): BelongsTo
    {
        return $this->belongsTo(CompanyCourseType::class);
    }

    /**
     * Get the user who performed the renewal.
     */
    public function renewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }
}
