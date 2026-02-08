<?php
//MailingListSignup::where('status', 'subscribed')->get(); //-----use tha thang!
namespace StuartPringle\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use StuartPringle\Newsletter\Mail\ConfirmNewsletter;
use StuartPringle\Newsletter\Models\MailingListSignup;

class NewsletterSignupController extends Controller
{
    public function store(Request $request)
    {
        $key = 'newsletter-signup:' . $request->ip();
        $maxAttempts = (int) config('newsletter.rate_limit.max_attempts', 5);
        $decaySeconds = (int) config('newsletter.rate_limit.decay_seconds', 60);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return back()->withErrors(['email' => 'Too many attempts. Try again later.']);
        }

        RateLimiter::hit($key, $decaySeconds);


        if ($request->filled(config('newsletter.honeypot_field', 'name'))) { // honeypot field
            $msg = 'Check your email to confirm your subscription!';
            return $request->expectsJson()
                ? response()->json(['message' => $msg])
                : back()->with('message', $msg);
        }

        $request->validate(['email' => 'required|email']);

        $existing = MailingListSignup::where('email', $request->email)->first();

        if ($existing && $existing->status === 'subscribed') {
            $msg = 'You’re already subscribed!';
            return $request->expectsJson()
                ? response()->json(['message' => $msg])
                : back()->with('message', $msg);
        }

        $signup = self::createSignup([
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
        ]);

        Mail::to($request->email)->send(new ConfirmNewsletter($signup));

        $msg = 'Check your email to confirm your subscription!';
        return $request->expectsJson()
            ? response()->json(['message' => $msg])
            : back()->with('message', $msg);
    }

    public static function createSignup(array $data): MailingListSignup
    {
        $email = $data['email'];
        $token = self::generateUniqueToken();

        return MailingListSignup::updateOrCreate(
            ['email' => $email],
            [
                'status' => $data['status'] ?? 'unconfirmed',
                'verification_token' => $token,
                'ip_address' => $data['ip'] ?? request()->ip(),
                'user_agent' => $data['user_agent'] ?? request()->userAgent(),
                'referrer' => $data['referrer'] ?? request()->headers->get('referer'),
            ]
        );
    }

    protected static function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
        } while (MailingListSignup::where('verification_token', $token)->exists());

        return $token;
    }


    public function showUnsubscribe($token)
    {
        $signup = MailingListSignup::where('verification_token', $token)->first();

        if (! $signup || $signup->status !== 'subscribed') {
            return view('newsletter::newsletter.unsubscribe')->with('message', 'Invalid or already unsubscribed.');
        }

        return view('newsletter::newsletter.confirm_unsubscribe', ['signup' => $signup]);
    }

    public function unsubscribe(Request $request, $token)
    {
        $signup = MailingListSignup::where('verification_token', $token)->first();

        if (! $signup || $signup->status !== 'subscribed') {
            return view('newsletter::newsletter.unsubscribe')->with('message', 'Invalid or already unsubscribed.');
        }

        $signup->update([
            'status' => 'unsubscribed',
            // token remains for resubscription or logging
        ]);

        return view('newsletter::newsletter.unsubscribe')->with('message', 'You’ve been unsubscribed successfully.');
    }


    public function confirm($token)
    {
        $signup = MailingListSignup::where('verification_token', $token)->firstOrFail();

        $signup->update([
            'status' => 'subscribed',
            //'verification_token' => null,
            'confirmed_at' => now(),
        ]);

        return view('newsletter::newsletter.confirmed');
    }
}
