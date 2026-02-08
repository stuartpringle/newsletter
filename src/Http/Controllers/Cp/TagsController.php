<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use StuartPringle\Newsletter\Models\Tag;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class TagsController extends Controller
{
    public function index()
    {
        $tenant = CurrentTenant::resolve();
        $tags = $tenant
            ? Tag::where('tenant_id', $tenant->id)->orderBy('name')->get()
            : collect();

        return view('newsletter::tags.index', compact('tags'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('newsletter::tags.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant, 400, 'Tenant not found.');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['tenant_id'] = $tenant->id;

        Tag::create($data);

        return redirect()->route('statamic.cp.newsletter.tags.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag)
    {
        $this->authorizeAdmin();
        $this->assertTenant($tag->tenant_id);

        return view('newsletter::tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorizeAdmin();
        $this->assertTenant($tag->tenant_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);

        $tag->update($data);

        return redirect()->route('statamic.cp.newsletter.tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorizeAdmin();
        $this->assertTenant($tag->tenant_id);

        $tag->delete();

        return back()->with('success', 'Tag deleted.');
    }

    protected function authorizeAdmin(): void
    {
        $tenant = CurrentTenant::resolve();
        $user = $tenant ? TenantUser::where('tenant_id', $tenant->id)->where('user_id', (string) auth()->id())->first() : null;
        abort_if(! $user || $user->role !== 'admin', 403, 'Admin role required.');
    }

    protected function assertTenant($tenantId): void
    {
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant || (int) $tenant->id !== (int) $tenantId, 404);
    }
}
