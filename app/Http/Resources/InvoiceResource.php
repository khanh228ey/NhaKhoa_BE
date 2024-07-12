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
            'customer' => [
                    'id' => $this->history->customer->id,
                    'name' => $this->history->customer->name,
                    'phone_number' => $this->history->customer->phone_number,
                    'birthday' => $this->history->customer->birthday,
                    'gender' => $this->history->customer->gender,
            ],
            'history' => [
                'id' => $this->history_id,
                'date' => $this->history->date,
                'time' => $this->history->time,
                'note' => $this->history->noted,
                // 'services' => $this->history->services ? $this->services->map(function ($service) {
                //     return [
                //         'id' => $service->id,
                //         'name' => $service->name,
                //         'image' => $service->image,
                //         'unit' => $service->unit,
                //         'quantity' => $service->pivot->quantity,
                //         'price' => $service->pivot->price,
                //     ];
                // })->toArray() : null,
                'services' => $this->history->services ? $this->history->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'image' => $service->image,
                        'unit' => $service->unit,
                        'quantity' => $service->pivot->quantity,
                        'price' => $service->pivot->price,
                    ];
                })->toArray() : null,
            ],
            'total_price' => $this->total_price,
            'method_payment' => $this->method_payment,
            'status' => $this->status,
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
