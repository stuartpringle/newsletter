<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use StuartPringle\Newsletter\Models\MailingList;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class ListsController extends Controller
{
    public function index()
    {
        $tenant = CurrentTenant::resolve();
        $lists = $tenant
            ? MailingList::where('tenant_id', $tenant->id)->orderBy('name')->get()
            : collect();

        return view('newsletter::lists.index', compact('lists'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('newsletter::lists.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant, 400, 'Tenant not found.');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['tenant_id'] = $tenant->id;

        MailingList::create($data);

        return redirect()->route('statamic.cp.newsletter.lists.index')->with('success', 'List created.');
    }

    public function edit(MailingList $list)
    {
        $this->authorizeAdmin();
        $this->assertTenant($list->tenant_id);

        return view('newsletter::lists.edit', compact('list'));
    }

    public function update(Request $request, MailingList $list)
    {
        $this->authorizeAdmin();
        $this->assertTenant($list->tenant_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        $list->update($data);

        return redirect()->route('statamic.cp.newsletter.lists.index')->with('success', 'List updated.');
    }

    public function destroy(MailingList $list)
    {
        $this->authorizeAdmin();
        $this->assertTenant($list->tenant_id);

        $list->delete();

        return back()->with('success', 'List deleted.');
    }

    protected function authorizeAdmin(): void
    {
        $tenant = CurrentTenant::resolve();
        $authUser = auth()->user();

        if ($authUser && method_exists($authUser, 'isSuper') && $authUser->isSuper()) {
            return;
        }

        if (! $tenant || ! Schema::hasTable('newsletter_tenant_user')) {
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
