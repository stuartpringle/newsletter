<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class MailingListSignup extends Model
{
    protected $table = 'mailing_list_signups';

    protected $fillable = [
        'email',
        'status',
        'ip_address',
        'user_agent',
        'referrer',
        'verification_token',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public $timestamps = true;
}
