<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page',
        'path',
        'ip_address',
        'user_agent',
        'referer',
        'visitor_key',
    ];

    protected static function booted(): void
    {
        static::creating(function (PageVisit $visit): void {
            $visit->created_at = $visit->freshTimestamp();
        });
    }

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
