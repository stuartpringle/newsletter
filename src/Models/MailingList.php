<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailingList extends Model
{
    protected $table = 'newsletter_mailing_lists';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(MailingListSignup::class, 'list_id');
    }
}
