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
        // return [
        //     'id' => $this->id,
        //     'date' => $this->date,
        //     'time' => $this->time,
        //     'noted' => $this->noted,
        //     'customer_id' => $this->customer->id,
        //     'customer_name' => $this->customer->name,
        //     'customer_birthday' => $this->customer->birthday,
        //     'doctor_id' => $this->doctor->id,
        //     'doctor_name' => $this->doctor->name,
        //     'services' =>$this->services ?  $this->services->map(function ($service) {
        //         return [
        //             'id' => $service->id,
        //             'name' => $service->name,
        //             'quantity' => $service->pivot->quantity,
        //             'price' => $service->pivot->price,
        //         ];
        //     }): null,
          
        // ];
        $data = [
            'id' => $this->id,
            'date' => $this->date,
            'time' => $this->time,
            'customer' => [
                    'id' => $this->Customer->id,
                    'name' => $this->Customer->name,
            ],
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
            ],
        ];
        if($request->route()->getName() === 'history.detail')  {
            $data = array_merge($data, [
                'noted' => $this->noted,
                'total_price' => $this->invoice->total_price,
                'services' =>$this->services ?  $this->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'quantity' => $service->pivot->quantity,
                        'price' => $service->pivot->price,
                    ];
                }): null,
            ]);
        }
    
        return $data;
    }
}
