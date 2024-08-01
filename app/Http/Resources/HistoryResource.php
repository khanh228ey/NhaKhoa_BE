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
        $data = [
            'id' => (int)$this->id,
            'date' => $this->date,
            'time' => $this->time,
            'status' => (int)$this->status,
            'customer' => [
                'id' => $this->Customer->id,
                'name' => $this->Customer->name,
                'phone_number' => $this->Customer->phone_number,
                'birthday' => $this->Customer->birthday,
                'gender' => (int)$this->Customer->gender,
                'address' => $this->Customer->address,
            ],
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
                'phone_number' => $this->doctor->phone_number,
                'gender' => $this->doctor->gender,
                'birthday' => $this->doctor->birthday,

            ],
        ];
        
        if ($request->route()->getName() === 'history.detail') {
            $data['customer']['histories'] = $this->Customer->histories ? $this->Customer->histories->map(function ($history) {
                return [
                    'id' => $history->id,
                    'date' => $history->date,
                    'time' => $history->time,
                    'total_price' => $history->invoice ? $history->invoice->total_price : null,
                    'doctor' => [
                        'id' => $history->doctor->id,
                        'name' => $history->doctor->name,
                    ],
                ];
            })->toArray() : null;
        
            $data = array_merge($data, [
                'note' => $this->noted,
                'total_price' => $this->invoice->total_price ?? null,
                'services' => $this->services ? $this->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'image' => $service->image,
                        'unit' => $service->unit,
                        'quantity' => $service->pivot->quantity,
                        'price' => $service->pivot->price,
                    ];
                })->toArray() : null,
            ]);
        }
        
        return $data;
        
    }
}
