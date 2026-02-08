<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StuartPringle\Newsletter\Models\EmailTemplate;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class TemplatesController extends Controller
{
    public function index()
    {
        $tenant = CurrentTenant::resolve();
        $templates = $tenant
            ? EmailTemplate::where('tenant_id', $tenant->id)->orderBy('name')->get()
            : collect();

        return view('newsletter::templates.index', compact('templates'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('newsletter::templates.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant, 400, 'Tenant not found.');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'html' => 'nullable|string',
        ]);

        $data['tenant_id'] = $tenant->id;

        EmailTemplate::create($data);

        return redirect()->route('statamic.cp.newsletter.templates.index')->with('success', 'Template created.');
    }

    public function edit(EmailTemplate $template)
    {
        $this->authorizeAdmin();
        $this->assertTenant($template->tenant_id);

        return view('newsletter::templates.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $this->authorizeAdmin();
        $this->assertTenant($template->tenant_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'html' => 'nullable|string',
        ]);

        $template->update($data);

        return redirect()->route('statamic.cp.newsletter.templates.index')->with('success', 'Template updated.');
    }

    public function destroy(EmailTemplate $template)
    {
        $this->authorizeAdmin();
        $this->assertTenant($template->tenant_id);

        $template->delete();

        return back()->with('success', 'Template deleted.');
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
