<?php

namespace StuartPringle\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use StuartPringle\Newsletter\Mail\ConfirmNewsletter;
use StuartPringle\Newsletter\Models\MailingList;
use StuartPringle\Newsletter\Models\MailingListSignup as NewsletterSubscriber;
use StuartPringle\Newsletter\Support\CurrentTenant;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $tenant = CurrentTenant::resolve();
        $query = NewsletterSubscriber::query()->with('list');

        if ($tenant) {
            $query->where('tenant_id', $tenant->id);
        }

        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%$search%");
        }

        $subscribers = $query->orderBy('created_at', 'desc')->paginate(25);
        $lists = $tenant ? MailingList::where('tenant_id', $tenant->id)->orderBy('name')->get() : collect();

        return view('newsletter::newsletter.index', compact('subscribers', 'lists'));
    }

    public function updateStatus(Request $request, NewsletterSubscriber $subscriber)
    {
        $subscriber->status = $request->input('status');
        $subscriber->save();
        return back()->with('success', 'Status updated.');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber deleted.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $tenant = CurrentTenant::resolve();
        $listId = (int) $request->input('list_id', 0);
        $list = null;

        if ($tenant && $listId) {
            $list = MailingList::where('tenant_id', $tenant->id)->where('id', $listId)->first();
        }

        if (! $list && $tenant) {
            $list = MailingList::where('tenant_id', $tenant->id)->orderBy('id')->first();
        }

        // Check if already exists and handle error
        $existsQuery = NewsletterSubscriber::where('email', $validated['email']);
        if ($tenant) {
            $existsQuery->where('tenant_id', $tenant->id);
        }
        if ($list) {
            $existsQuery->where('list_id', $list->id);
        }

        if ($existsQuery->exists()) {
            return back()->with('error', 'That email is already on the list.');
        }

        // Use centralized logic
        $subscriber = NewsletterSignupController::createSignup([
            'email' => $validated['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'status' => $request->status,
            'tenant_id' => $tenant?->id,
            'list_id' => $list?->id,
        ]);

        if($request->send_email) {
            Mail::to($subscriber->email)->send(new ConfirmNewsletter($subscriber));
        }

        // dispatch confirmation logic if needed
        return back()->with('success', 'Subscriber added.');
    }


    public function resend(NewsletterSubscriber $subscriber)
    {
        Mail::to($subscriber->email)->send(new ConfirmNewsletter($subscriber));

        return back()->with('success', 'Confirmation email resent.');
    }

}
