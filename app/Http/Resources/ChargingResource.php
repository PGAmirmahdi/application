<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'charging_id' => $this->id,
            'amount' => $this->amount,
            'pay-status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type'=>$this->type,
            'description'=>$this->description,
            'wallet_id'=>$this->wallet_id,
            'tracking_code'=>$this->tracking_code,
            'pay_status' => $this->payment->status,
            'pay_status_text' => Payment::STATUS[$this->payment->status],
            'pay_status_color' => Payment::STATUS_COLOR[$this->payment->status],
            'authority' => $this->payment ? 'https://www.zarinpal.com/pg/StartPay/'.$this->payment->authority : null,
        ];
    }
}
