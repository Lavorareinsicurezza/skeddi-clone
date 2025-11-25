<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'vat_number',
        'tax_code',
        'ateco',
        'sdi',
        'registered_office',
        'operating_office',
        'main_email',
        'pec_email',
        'phone',
        'phone_2',
        'company_contact_person',
        'employer',
        'head_of_prevention',
        'workers_safety_representative',
        'company_doctor',
        'workplace_safety_risk',
        'subject_to_cpi',
        'rischio_antincendio',
        'accountant_name',
        'accountant_phone',
        'accountant_email',
        'labor_consultant_name',
        'labor_consultant_phone',
        'labor_consultant_email',
        'notes',
        'send_deadline_notification',
        'freeze_company',
        'contacts',
        'legal_address_street',
        'legal_address_number',
        'legal_address_postal',
        'legal_address_city',
        'operating_address_street',
        'operating_address_number',
        'operating_address_postal',
        'operating_address_city',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subject_to_cpi' => 'boolean',
        'send_deadline_notification' => 'boolean',
        'freeze_company' => 'boolean',
        'contacts' => 'array',
    ];

    /**
     * Get the users for the company.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeCompany($query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }

    public function childCompanies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
