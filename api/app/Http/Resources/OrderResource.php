<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'crypto' => $this->crypto->name,
            'user_id' => $this->user_id,
            'amount_thbt' => $this->amount_thbt,
            'amount_crypto' => $this->amount_crypto,
            'price' => $this->price,
            'slippage' => $this->slippage,
            'date_time' => (string)$this->created_at
        ];
    }
}
