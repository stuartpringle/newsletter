<?php

namespace StuartPringle\Newsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use StuartPringle\Newsletter\Models\CampaignSend;
use StuartPringle\Newsletter\Models\CampaignEvent;

class TrackingController extends Controller
{
    public function open(CampaignSend $send)
    {
        CampaignEvent::create([
            'campaign_id' => $send->campaign_id,
            'subscriber_id' => $send->subscriber_id,
            'send_id' => $send->id,
            'type' => 'open',
            'message_id' => $send->message_id,
            'occurred_at' => now(),
        ]);

        $path = __DIR__.'/../../resources/pixel.gif';
        return new BinaryFileResponse($path, 200, ['Content-Type' => 'image/gif']);
    }

    public function click(Request $request, CampaignSend $send)
    {
        $url = $request->query('url');
        if (! $url) {
            abort(404);
        }

        CampaignEvent::create([
            'campaign_id' => $send->campaign_id,
            'subscriber_id' => $send->subscriber_id,
            'send_id' => $send->id,
            'type' => 'click',
            'url' => $url,
            'message_id' => $send->message_id,
            'occurred_at' => now(),
        ]);

        return redirect()->away($url);
    }
}
