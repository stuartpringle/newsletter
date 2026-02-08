<?php

namespace StuartPringle\Newsletter\Support;

use DOMDocument;
use StuartPringle\Newsletter\Models\Campaign;
use StuartPringle\Newsletter\Models\CampaignSend;
use StuartPringle\Newsletter\Models\MailingListSignup;

class CampaignRenderer
{
    public static function render(Campaign $campaign, MailingListSignup $subscriber, CampaignSend $send): string
    {
        $html = $campaign->html;
        if (! $html && $campaign->template) {
            $html = $campaign->template->html;
        }

        $html = $html ?: '';

        $html = self::replaceMergeTags($html, $subscriber);
        $html = self::injectTracking($html, $send);
        $html = self::ensureUnsubscribe($html, $subscriber);

        return $html;
    }

    protected static function replaceMergeTags(string $html, MailingListSignup $subscriber): string
    {
        $replacements = [
            '{{ subscriber.email }}' => $subscriber->email,
            '{{ subscriber.name }}' => $subscriber->name ?? '',
        ];

        return strtr($html, $replacements);
    }

    protected static function injectTracking(string $html, CampaignSend $send): string
    {
        if ($html === '') {
            return $html;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            if (! $href) {
                continue;
            }
            if (str_starts_with($href, 'mailto:') || str_starts_with($href, '#')) {
                continue;
            }
            $tracked = url('/newsletter/track/click/'.$send->id).'?url='.urlencode($href);
            $link->setAttribute('href', $tracked);
        }

        $pixel = $dom->createElement('img');
        $pixel->setAttribute('src', url('/newsletter/track/open/'.$send->id));
        $pixel->setAttribute('alt', '');
        $pixel->setAttribute('width', '1');
        $pixel->setAttribute('height', '1');
        $pixel->setAttribute('style', 'display:block;');
        $dom->getElementsByTagName('body')->item(0)?->appendChild($pixel);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body) {
            return $html;
        }

        $out = '';
        foreach ($body->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    protected static function ensureUnsubscribe(string $html, MailingListSignup $subscriber): string
    {
        $unsubscribe = url('/newsletter/preferences/'.$subscriber->verification_token);

        if (str_contains($html, $unsubscribe)) {
            return $html;
        }

        return $html."<p style=\"font-size:12px;color:#666;\">".
            "<a href=\"{$unsubscribe}\">Manage preferences</a>".
            "</p>";
    }
}
