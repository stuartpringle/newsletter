<?php

namespace StuartPringle\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SegmentRule extends Model
{
    protected $table = 'newsletter_segment_rules';

    protected $fillable = [
        'segment_id',
        'field',
        'operator',
        'value',
    ];

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class, 'segment_id');
    }
}
