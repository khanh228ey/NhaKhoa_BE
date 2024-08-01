<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
                'id' => (int)$this->id,
                'name' => $this->name,
                'phone' => $this->phone,
                'date' => $this->date,
                'time' => $this->time,
                'status'=> (int)$this->status,
        ];
        if($request->route()->getName() === 'appointment.detail')  {
            $data = array_merge($data, [
                'note' => $this->note,
               'doctor' => $this->doctor ? [
                        'id' => $this->doctor->id,
                        'name' => $this->doctor->name,
                    ] : null,
                    'services' => $this->services ? $this->services->map(function ($service) {
                        return [
                            'id' => (int)$service->id,
                            'name' => $service->name,
                            'image' => $service->image,
                            'unit' => $service->unit,
                            'min_price' => (int)$service->min_price
                        ];
                    }) : null,
            ]);
        }
        return $data;
    }
}
