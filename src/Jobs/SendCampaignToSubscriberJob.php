<?php

namespace StuartPringle\Newsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use StuartPringle\Newsletter\Models\Campaign;
use StuartPringle\Newsletter\Models\CampaignSend;
use StuartPringle\Newsletter\Models\MailingListSignup;
use StuartPringle\Newsletter\Support\CampaignRenderer;

class SendCampaignToSubscriberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $campaignId, public int $subscriberId)
    {
    }

    public function handle(): void
    {
        $campaign = Campaign::with('template')->find($this->campaignId);
        $subscriber = MailingListSignup::find($this->subscriberId);

        if (! $campaign || ! $subscriber || $subscriber->status !== 'subscribed') {
            return;
        }

        $send = CampaignSend::firstOrCreate([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ], [
            'status' => 'queued',
        ]);

        $html = CampaignRenderer::render($campaign, $subscriber, $send);
        $subject = $campaign->subject ?: $campaign->template?->subject ?: 'Newsletter';
        $fromName = $campaign->from_name ?: $campaign->template?->from_name;
        $fromEmail = $campaign->from_email ?: $campaign->template?->from_email;

        $from = $fromEmail ? ($fromName ? "{$fromName} <{$fromEmail}>" : $fromEmail) : config('mail.from.address');

        $messageId = Mail::html($html, function ($message) use ($subscriber, $subject, $from) {
            $message->to($subscriber->email);
            $message->subject($subject);
            if ($from) {
                $message->from($from);
            }
        });

        $send->update([
            'status' => 'sent',
            'sent_at' => now(),
            'message_id' => is_string($messageId) ? $messageId : null,
        ]);
    }
}
