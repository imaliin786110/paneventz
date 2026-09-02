<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'wedding_date',
        'wedding_location',
        'service',
        'message',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'wedding_date' => 'date',
    ];
}