<?php

namespace StuartPringle\Newsletter\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StuartPringle\Newsletter\Models\Segment;
use StuartPringle\Newsletter\Models\SegmentRule;
use StuartPringle\Newsletter\Models\TenantUser;
use StuartPringle\Newsletter\Support\CurrentTenant;

class SegmentsController extends Controller
{
    public function index()
    {
        $tenant = CurrentTenant::resolve();
        $segments = $tenant
            ? Segment::where('tenant_id', $tenant->id)->orderBy('name')->get()
            : collect();

        return view('newsletter::segments.index', compact('segments'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('newsletter::segments.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $tenant = CurrentTenant::resolve();
        abort_if(! $tenant, 400, 'Tenant not found.');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'array',
            'rules.*.field' => 'required|string|max:255',
            'rules.*.operator' => 'required|string|max:50',
            'rules.*.value' => 'nullable|string',
        ]);

        $segment = Segment::create([
            'tenant_id' => $tenant->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->syncRules($segment, $data['rules'] ?? []);

        return redirect()->route('statamic.cp.newsletter.segments.index')->with('success', 'Segment created.');
    }

    public function edit(Segment $segment)
    {
        $this->authorizeAdmin();
        $this->assertTenant($segment->tenant_id);

        $segment->load('rules');

        return view('newsletter::segments.edit', compact('segment'));
    }

    public function update(Request $request, Segment $segment)
    {
        $this->authorizeAdmin();
        $this->assertTenant($segment->tenant_id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'array',
            'rules.*.field' => 'required|string|max:255',
            'rules.*.operator' => 'required|string|max:50',
            'rules.*.value' => 'nullable|string',
        ]);

        $segment->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->syncRules($segment, $data['rules'] ?? []);

        return redirect()->route('statamic.cp.newsletter.segments.index')->with('success', 'Segment updated.');
    }

    public function destroy(Segment $segment)
    {
        $this->authorizeAdmin();
        $this->assertTenant($segment->tenant_id);

        $segment->delete();

        return back()->with('success', 'Segment deleted.');
    }

    protected function syncRules(Segment $segment, array $rules): void
    {
        SegmentRule::where('segment_id', $segment->id)->delete();

        foreach ($rules as $rule) {
            SegmentRule::create([
                'segment_id' => $segment->id,
                'field' => $rule['field'],
                'operator' => $rule['operator'],
                'value' => $rule['value'] ?? null,
            ]);
        }
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
