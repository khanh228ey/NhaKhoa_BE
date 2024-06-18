<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'total_price' => $this->total_price,
            'method_payment' => $this->method_payment,
            'status' => $this->status,
            'history' => [
                'id' => $this->history->id,
                'date' => $this->history->date,
                'time' => $this->history->time,
                'customer' => [
                    'id' => $this->history->customer->id,
                    'name' => $this->history->customer->name,
                    'phone_number' => $this->history->customer->phone_number,
                    'birthday' => $this->history->customer->birthday,
                ],
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'phone_number' => $this->user->phone_number,
            ],
            // 'customer_name' => optional($this->history->customer)->name,
        ];
    }
}
