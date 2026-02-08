<?php

namespace StuartPringle\Newsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use StuartPringle\Newsletter\Models\Campaign;
use StuartPringle\Newsletter\Models\MailingListSignup;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $campaignId)
    {
    }

    public function handle(): void
    {
        $campaign = Campaign::find($this->campaignId);
        if (! $campaign || ! $campaign->list_id) {
            return;
        }

        $query = MailingListSignup::where('list_id', $campaign->list_id)
            ->where('status', 'subscribed');

        $query->chunkById(500, function ($subscribers) use ($campaign) {
            foreach ($subscribers as $subscriber) {
                SendCampaignToSubscriberJob::dispatch($campaign->id, $subscriber->id);
            }
        });
    }
}
