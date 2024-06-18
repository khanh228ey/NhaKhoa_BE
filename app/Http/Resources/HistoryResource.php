<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
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
            'date' => $this->date,
            'time' => $this->time,
            'noted' => $this->noted,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'birthday' => $this->customer->birthday,
            ],
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
            ],
            'services' =>$this->services ?  $this->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'quantity' => $service->pivot->quantity,
                    'price' => $service->pivot->price,
                ];
            }): null,
          
        ];
    }
}
