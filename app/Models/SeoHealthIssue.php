<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoHealthIssue extends Model
{
    protected $fillable = [
        'item_type',
        'item_title',
        'url',
        'edit_url',
        'issue_code',
        'message',
        'severity',
        'is_resolved',
        'last_detected_at',
    ];

    protected $casts = [
        'is_resolved'       => 'boolean',
        'last_detected_at'  => 'datetime',
    ];
}
