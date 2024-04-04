<?php

namespace App\Http\Resources;

use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'text' => Verta::parse($this->date)->formatWord('l'),
            'is_holiday' => (bool)$this->is_holiday,
        ];
    }
}
