<?php

namespace StuartPringle\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use StuartPringle\Newsletter\Models\CampaignEvent;
use StuartPringle\Newsletter\Models\CampaignSend;

class WebhookController extends Controller
{
    public function postmark(Request $request)
    {
        $type = $request->input('RecordType');
        $messageId = $request->input('MessageID');
        $recipient = $request->input('Recipient');
        $url = $request->input('OriginalLink');

        $send = $messageId ? CampaignSend::where('message_id', $messageId)->first() : null;

        if (! $send) {
            return response()->json(['ok' => true]);
        }

        $eventType = match ($type) {
            'Open' => 'open',
            'Click' => 'click',
            'Bounce' => 'bounce',
            'SpamComplaint' => 'spam',
            default => 'event',
        };

        CampaignEvent::create([
            'campaign_id' => $send->campaign_id,
            'subscriber_id' => $send->subscriber_id,
            'send_id' => $send->id,
            'type' => $eventType,
            'url' => $url,
            'message_id' => $messageId,
            'meta' => $request->all(),
            'occurred_at' => now(),
        ]);

        if ($eventType === 'bounce' || $eventType === 'spam') {
            $send->subscriber?->update(['status' => 'unsubscribed']);
        }

        return response()->json(['ok' => true]);
    }
}
