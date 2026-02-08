<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StuartPringle\Newsletter\Models\Campaign;
use StuartPringle\Newsletter\Models\EmailTemplate;
use StuartPringle\Newsletter\Models\MailingList;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class CampaignsController extends Controller
{
    public function index()
    {
        $tenant = CurrentTenant::resolve();
        $campaigns = $tenant
            ? Campaign::where('tenant_id', $tenant->id)->orderByDesc('created_at')->get()
            : collect();

        return view('newsletter::campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $tenant = CurrentTenant::resolve();

        $lists = $tenant ? MailingList::where('tenant_id', $tenant->id)->orderBy('name')->get() : collect();
        $templates = $tenant ? EmailTemplate::where('tenant_id', $tenant->id)->orderBy('name')->get() : collect();

        return view('newsletter::campaigns.create', compact('lists', 'templates'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant, 400, 'Tenant not found.');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'list_id' => 'nullable|integer',
            'template_id' => 'nullable|integer',
            'subject' => 'nullable|string|max:255',
            'preview_text' => 'nullable|string|max:255',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'html' => 'nullable|string',
        ]);

        $data['tenant_id'] = $tenant->id;
        $data['status'] = 'draft';

        Campaign::create($data);

        return redirect()->route('statamic.cp.newsletter.campaigns.index')->with('success', 'Campaign created.');
    }

    public function edit(Campaign $campaign)
    {
        $this->authorizeAdmin();
        $this->assertTenant($campaign->tenant_id);

        $tenant = CurrentTenant::resolve();
        $lists = $tenant ? MailingList::where('tenant_id', $tenant->id)->orderBy('name')->get() : collect();
        $templates = $tenant ? EmailTemplate::where('tenant_id', $tenant->id)->orderBy('name')->get() : collect();

        return view('newsletter::campaigns.edit', compact('campaign', 'lists', 'templates'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorizeAdmin();
        $this->assertTenant($campaign->tenant_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'list_id' => 'nullable|integer',
            'template_id' => 'nullable|integer',
            'subject' => 'nullable|string|max:255',
            'preview_text' => 'nullable|string|max:255',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'html' => 'nullable|string',
        ]);

        $campaign->update($data);

        return redirect()->route('statamic.cp.newsletter.campaigns.index')->with('success', 'Campaign updated.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorizeAdmin();
        $this->assertTenant($campaign->tenant_id);

        $campaign->delete();

        return back()->with('success', 'Campaign deleted.');
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
        abort_if(! $user || $user->role !== 'admin', 403, 'Admin role required.');
    }

    protected function assertTenant($tenantId): void
    {
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant || (int) $tenant->id !== (int) $tenantId, 404);
    }
}
