<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'category' => $this->category?->name,
            'category_slug' => $this->category?->slug,
            'cover_image_path' => $this->cover_image_path,
            'location' => $this->location,
            'year' => $this->year,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at,
        ];
    }
}
