<?php

namespace StuartPringle\Newsletter\Support;

use Illuminate\Support\Facades\Auth;
use StuartPringle\Newsletter\Models\Tenant;
use StuartPringle\Newsletter\Models\TenantUser;

class CurrentTenant
{
    public static function resolve(): ?Tenant
    {
        $user = Auth::user();

        if ($user) {
            $tenantId = TenantUser::where('user_id', (string) $user->id())
                ->value('tenant_id');

            if ($tenantId) {
                return Tenant::find($tenantId);
            }
        }

        $defaultTenantId = config('newsletter.tenant.default_tenant_id');
        if ($defaultTenantId) {
            return Tenant::find($defaultTenantId);
        }

        return Tenant::query()->orderBy('id')->first();
    }
}
