<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->getSubTitle(),
            'discount' => $this->discount,
            'price_with_ticket_discount' =>$this->when($this->webinar,$this->webinar->price - $this->webinar->getDiscount($this)) ,
            'is_valid' => $this->isValid(),
        ];
    }
}
