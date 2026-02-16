<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    protected $fillable = [
        'company_id',
        'notification_periods',
        'smtp_address',
        'smtp_alias',
        'smtp_reply_to',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'email_auto_generated',
        'whatsapp_notification',
        'email_template',
        'notification_subject',
        'notification_body',
        'whatsapp_smtp_address',
        'whatsapp_smtp_alias',
        'whatsapp_smtp_reply_to',
        'whatsapp_api_url',
        'whatsapp_api_key',
        'whatsapp_phone_number_id',
        'whatsapp_business_account_id',
        'whatsapp_template_name',
    ];

    protected $casts = [
        'email_auto_generated' => 'boolean',
        'whatsapp_notification' => 'boolean',
        'notification_periods' => 'array',
    ];

    /**
     * Get the company that owns the setting.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope to filter by company.
     */
    public function scopeCompany($query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }
}
