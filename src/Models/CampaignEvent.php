<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignEvent extends Model
{
    protected $table = 'newsletter_campaign_events';

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'send_id',
        'type',
        'url',
        'message_id',
        'meta',
        'occurred_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(MailingListSignup::class, 'subscriber_id');
    }

    public function send(): BelongsTo
    {
        return $this->belongsTo(CampaignSend::class, 'send_id');
    }
}
