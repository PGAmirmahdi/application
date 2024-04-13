<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'count' => $this->count,
            'price' => $this->price,
            'price_text' => number_format($this->price).' تومان',
            'total_price' => $this->total_price,
            'total_price_text' => number_format($this->total_price).' تومان',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
