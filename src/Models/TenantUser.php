<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    protected $table = 'newsletter_tenant_user';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'role',
    ];
}
