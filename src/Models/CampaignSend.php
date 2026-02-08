<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignSend extends Model
{
    protected $table = 'newsletter_campaign_sends';

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'message_id',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(MailingListSignup::class, 'subscriber_id');
    }
}
