<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailingListSignup extends Model
{
    protected $table = 'mailing_list_signups';

    protected $fillable = [
        'email',
        'tenant_id',
        'list_id',
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(MailingList::class, 'list_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'newsletter_subscriber_tag', 'subscriber_id', 'tag_id');
    }
}
