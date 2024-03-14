<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'province' => $this->province->name,
            'city' => $this->city,
            'address' => $this->address,
            'status' => $this->status,
            'status_text' => Order::STATUS[$this->status],
            'status_color' => Order::STATUS_COLOR[$this->status],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
