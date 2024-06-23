<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

class
OrderResource extends JsonResource
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
            'tracking_code' => $this->payment->tracking_code,
            'authority' => $this->payment ? 'https://www.zarinpal.com/pg/StartPay/'.$this->payment->authority : null,
            'status_text' => Order::STATUS[$this->status],
            'status_color' => Order::STATUS_COLOR[$this->status],
            'pay_status' => $this->payment->status,
            'pay_status_text' => Payment::STATUS[$this->payment->status],
            'pay_status_color' => Payment::STATUS_COLOR[$this->payment->status],
            'total_price' => $this->items()->sum('total_price'),
            'discount'=>$this->discount,
            'total_price_text' => number_format($this->items()->sum('total_price')).' تومان',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
