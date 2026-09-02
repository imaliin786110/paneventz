<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'event_date',
        'event_location',
        'services',
        'budget',
        'message',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'event_date' => 'date',
        'services' => 'array',
    ];

    public function getFormattedServicesAttribute(): string
    {
        if (empty($this->services)) {
            return '—';
        }

        return is_array($this->services) ? implode(', ', $this->services) : (string) $this->services;
    }
}
