<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperatingLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'address_street',
        'address_number',
        'address_postal',
        'address_city',
        'site_contact_name',
        'site_contact_phone',
        'site_contact_email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Worker::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function scopeCompany($query)
    {
        return $query->where('company_id', session('selectedCompanyId'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [];
        
        if ($this->address_street) {
            $parts[] = $this->address_street;
        }
        
        if ($this->address_number) {
            $parts[] = $this->address_number;
        }
        
        if ($this->address_city) {
            $parts[] = $this->address_city;
        }
        
        if ($this->address_postal) {
            $parts[] = $this->address_postal;
        }
        
        return implode(', ', $parts);
    }
}