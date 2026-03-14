<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\City;
use App\Enums\ProfileHeadlinesEnum;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'city_id',
        'years_experience',
        'avatar_path',
    ];

    protected $casts = [
        'headline' => ProfileHeadlinesEnum::class,
        'years_experience' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
