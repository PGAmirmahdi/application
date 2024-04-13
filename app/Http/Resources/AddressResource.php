<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'province_id' => $this->province_id,
            'city' => $this->city,
            'full_address' => $this->full_address,
            'postal_code' => $this->postal_code,
            'location' => $this->location ? json_decode($this->location) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
