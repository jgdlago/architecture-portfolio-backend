<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('user');

        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'phone' => $this->phone,
            'professional_email' => $this->professional_email,
            'linkedin_url' => $this->linkedin_url,
            'instagram_url' => $this->instagram_url,
            'whatsapp_url' => $this->whatsapp_url
        ];
    }
}
