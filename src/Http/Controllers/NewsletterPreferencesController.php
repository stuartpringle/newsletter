<?php

namespace StuartPringle\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StuartPringle\Newsletter\Models\MailingListSignup;

class NewsletterPreferencesController extends Controller
{
    public function show($token)
    {
        $signup = MailingListSignup::where('verification_token', $token)->first();

        if (! $signup) {
            return view('newsletter::preferences.invalid');
        }

        $signups = MailingListSignup::where('email', $signup->email)
            ->where('tenant_id', $signup->tenant_id)
            ->with('list')
            ->get();

        return view('newsletter::preferences.show', compact('signup', 'signups'));
    }

    public function update(Request $request, $token)
    {
        $signup = MailingListSignup::where('verification_token', $token)->first();

        if (! $signup) {
            return view('newsletter::preferences.invalid');
        }

        $data = $request->validate([
            'unsubscribe_all' => 'nullable|boolean',
            'lists' => 'array',
            'lists.*' => 'integer',
        ]);

        $signups = MailingListSignup::where('email', $signup->email)
            ->where('tenant_id', $signup->tenant_id)
            ->get();

        if (($data['unsubscribe_all'] ?? false) === true) {
            foreach ($signups as $s) {
                $s->update(['status' => 'unsubscribed']);
            }
            return view('newsletter::preferences.updated');
        }

        $listIds = collect($data['lists'] ?? [])->map(fn ($id) => (int) $id)->all();

        foreach ($signups as $s) {
            $shouldBeSubscribed = in_array((int) $s->list_id, $listIds, true);
            $s->update(['status' => $shouldBeSubscribed ? 'subscribed' : 'unsubscribed']);
        }

        return view('newsletter::preferences.updated');
    }
}
