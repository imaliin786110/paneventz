<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchConsoleMetric extends Model
{
    protected $fillable = [
        'query',
        'page_url',
        'clicks',
        'impressions',
        'ctr',
        'position',
        'opportunity_type',
        'ai_recommendation',
        'metric_date',
    ];

    protected $casts = [
        'clicks'        => 'integer',
        'impressions'   => 'integer',
        'ctr'           => 'decimal:2',
        'position'      => 'decimal:2',
        'metric_date'   => 'date',
    ];
}
