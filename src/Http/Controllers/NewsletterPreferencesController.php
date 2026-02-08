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

        return view('newsletter::preferences.show', compact('signup'));
    }

    public function update(Request $request, $token)
    {
        $signup = MailingListSignup::where('verification_token', $token)->first();

        if (! $signup) {
            return view('newsletter::preferences.invalid');
        }

        $data = $request->validate([
            'unsubscribe' => 'nullable|boolean',
        ]);

        if (($data['unsubscribe'] ?? false) === true) {
            $signup->update(['status' => 'unsubscribed']);
            return view('newsletter::preferences.updated');
        }

        return view('newsletter::preferences.updated');
    }
}
