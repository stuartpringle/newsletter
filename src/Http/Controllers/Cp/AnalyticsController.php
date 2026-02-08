<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use StuartPringle\Newsletter\Models\Campaign;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class AnalyticsController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $tenant = CurrentTenant::resolve();
        $query = Campaign::query();

        if ($tenant) {
            $query->where('tenant_id', $tenant->id);
        }

        $campaigns = $query->orderByDesc('created_at')->get()->map(function (Campaign $campaign) {
            $stats = DB::table('newsletter_campaign_events')
                ->selectRaw("sum(case when type = 'open' then 1 else 0 end) as opens")
                ->selectRaw("sum(case when type = 'click' then 1 else 0 end) as clicks")
                ->selectRaw("sum(case when type = 'bounce' then 1 else 0 end) as bounces")
                ->selectRaw("sum(case when type = 'spam' then 1 else 0 end) as spams")
                ->where('campaign_id', $campaign->id)
                ->first();

            $sent = DB::table('newsletter_campaign_sends')->where('campaign_id', $campaign->id)->count();

            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'status' => $campaign->status,
                'sent' => $sent,
                'opens' => (int) ($stats->opens ?? 0),
                'clicks' => (int) ($stats->clicks ?? 0),
                'bounces' => (int) ($stats->bounces ?? 0),
                'spams' => (int) ($stats->spams ?? 0),
                'created_at' => $campaign->created_at,
            ];
        });

        return view('newsletter::analytics.index', compact('campaigns'));
    }

    protected function authorizeAdmin(): void
    {
        $tenant = CurrentTenant::resolve();
        $authUser = auth()->user();

        if ($authUser && method_exists($authUser, 'isSuper') && $authUser->isSuper()) {
            return;
        }

        if (! $tenant) {
            return;
        }

        if (! TenantUser::where('tenant_id', $tenant->id)->exists()) {
            return;
        }

        $user = TenantUser::where('tenant_id', $tenant->id)->where('user_id', (string) auth()->id())->first();
        abort_if(! $user, 403, 'Admin role required.');
    }
}
