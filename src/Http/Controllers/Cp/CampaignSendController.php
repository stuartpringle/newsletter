<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Routing\Controller;
use StuartPringle\Newsletter\Jobs\SendCampaignJob;
use StuartPringle\Newsletter\Models\Campaign;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class CampaignSendController extends Controller
{
    public function send(Campaign $campaign)
    {
        $this->authorizeAdmin($campaign->tenant_id);

        SendCampaignJob::dispatch($campaign->id);

        $campaign->update(['status' => 'sending']);

        return back()->with('success', 'Campaign queued for sending.');
    }

    protected function authorizeAdmin($tenantId): void
    {
        $tenant = CurrentTenant::resolve();
        $authUser = auth()->user();

        if ($authUser && method_exists($authUser, 'isSuper') && $authUser->isSuper()) {
            return;
        }

        if (! $tenant || (int) $tenant->id !== (int) $tenantId) {
            abort(404);
        }

        if (! TenantUser::where('tenant_id', $tenant->id)->exists()) {
            return;
        }

        $user = TenantUser::where('tenant_id', $tenant->id)->where('user_id', (string) auth()->id())->first();
        abort_if(! $user || $user->role !== 'admin', 403, 'Admin role required.');
    }
}
