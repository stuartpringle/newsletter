<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $table = 'newsletter_tenants';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function lists(): HasMany
    {
        return $this->hasMany(MailingList::class, 'tenant_id');
    }
}
