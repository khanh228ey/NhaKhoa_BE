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
        $data = [
            'id' => $this->id,
            'history_id' =>$this->history_id,
            'total_price' => $this->total_price,
            'method_payment' => $this->method_payment,
            'status' => $this->status,
            'customer' => [
                    'id' => $this->history->customer->id,
                    'name' => $this->history->customer->name,
            ]
        ];
        if($request->route()->getName() === 'invoice.detail')  {
            $data = array_merge($data, [
                'user' => $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'phone_number' => $this->user->phone_number
                ] : null,
            ]);
    
    }
    return $data;
    }
}
