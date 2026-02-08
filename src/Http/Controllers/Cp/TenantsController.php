<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Statamic\Facades\User;
use StuartPringle\Newsletter\Models\Tenant;
use StuartPringle\Newsletter\Models\TenantUser;

class TenantsController extends Controller
{
    public function index()
    {
        $this->authorizeSystemAdmin();
        $tenants = Tenant::orderBy('name')->get();

        return view('newsletter::tenants.index', compact('tenants'));
    }

    public function create()
    {
        $this->authorizeSystemAdmin();
        return view('newsletter::tenants.create');
    }

    public function store(Request $request)
    {
        $this->authorizeSystemAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:newsletter_tenants,slug',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        $tenant = Tenant::create($data);

        return redirect()->route('statamic.cp.newsletter.tenants.edit', $tenant)->with('success', 'Tenant created.');
    }

    public function edit(Tenant $tenant)
    {
        $this->authorizeSystemAdmin();

        $members = TenantUser::where('tenant_id', $tenant->id)->get();
        $users = User::query()->get();

        return view('newsletter::tenants.edit', compact('tenant', 'members', 'users'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $this->authorizeSystemAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:newsletter_tenants,slug,'.$tenant->id,
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        $tenant->update($data);

        return redirect()->route('statamic.cp.newsletter.tenants.edit', $tenant)->with('success', 'Tenant updated.');
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorizeSystemAdmin();
        $tenant->delete();

        return back()->with('success', 'Tenant deleted.');
    }

    public function addMember(Request $request, Tenant $tenant)
    {
        $this->authorizeSystemAdmin();

        $data = $request->validate([
            'user_id' => 'required|string',
            'role' => 'required|in:admin,user',
        ]);

        TenantUser::updateOrCreate(
            ['tenant_id' => $tenant->id, 'user_id' => $data['user_id']],
            ['role' => $data['role']]
        );

        return back()->with('success', 'Member added.');
    }

    public function updateMember(Request $request, Tenant $tenant, TenantUser $member)
    {
        $this->authorizeSystemAdmin();
        abort_if($member->tenant_id !== $tenant->id, 404);

        $data = $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $member->update(['role' => $data['role']]);

        return back()->with('success', 'Member updated.');
    }

    public function removeMember(Tenant $tenant, TenantUser $member)
    {
        $this->authorizeSystemAdmin();
        abort_if($member->tenant_id !== $tenant->id, 404);

        $member->delete();

        return back()->with('success', 'Member removed.');
    }

    protected function authorizeSystemAdmin(): void
    {
        $authUser = auth()->user();

        if ($authUser && method_exists($authUser, 'isSuper') && $authUser->isSuper()) {
            return;
        }

        if (! Schema::hasTable('newsletter_tenant_user')) {
            return;
        }

        if (! TenantUser::query()->exists()) {
            return;
        }

        abort(403, 'Admin role required.');
    }
}
