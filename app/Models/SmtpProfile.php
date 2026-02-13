<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SmtpProfile extends Model
{
    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'from_address',
        'from_name',
        'reply_to',
        'encryption',
    ];

    /**
     * Get the password attribute (decrypt).
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Crypt::decryptString($value) : null,
            set: fn ($value) => $value ? Crypt::encryptString($value) : null,
        );
    }
}
