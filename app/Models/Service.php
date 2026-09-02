<?php

namespace App\Models;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSeo;

    protected $fillable = ['name', 'short_description', 'description', 'price_from', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['price_from' => 'decimal:2', 'is_published' => 'boolean'];
    }
}
