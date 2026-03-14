<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
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
            'description' => $this->description,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'cover_image_path' => $this->cover_image_path,
            'location' => $this->location,
            'year' => $this->year,
            'area_m2' => $this->area_m2,
            'additional_info' => $this->additional_info,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at,
            'images' => ProjectImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
