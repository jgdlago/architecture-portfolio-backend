<?php

namespace App\Http\Resources;

use App\Enums\ProfileHeadlinesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $headlineValue = $this->headline?->value;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bio' => $this->bio,
            'headline' => [
                'value' => $headlineValue,
                'label' => $headlineValue ? ProfileHeadlinesEnum::label()[$headlineValue] ?? $headlineValue : null,
            ],
            'city' => $this->city,
            'years_experience' => $this->years_experience,
            'avatar_path' => $this->avatar_path,
        ];
    }
}
