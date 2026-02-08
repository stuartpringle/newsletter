<?php

namespace StuartPringle\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use StuartPringle\Newsletter\Mail\ConfirmNewsletter;
use StuartPringle\Newsletter\Models\MailingListSignup as NewsletterSubscriber;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%$search%");
        }

        $subscribers = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('newsletter::newsletter.index', compact('subscribers'));
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

        // Check if already exists and handle error
        if (NewsletterSubscriber::where('email', $validated['email'])->exists()) {
            return back()->with('error', 'That email is already on the list.');
        }

        // Use centralized logic
        $subscriber = NewsletterSignupController::createSignup([
            'email' => $validated['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'status' => $request->status,
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
