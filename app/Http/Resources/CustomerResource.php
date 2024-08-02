<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'email' => $this->email,
            'gender' => (int)$this->gender,
            'address' => $this->address,
            'histories' => $this->Histories->map(function ($history) {
                return [
                    'id' => (int)$history->id,
                    'date' => $history->date,
                    'time' => $history->time,
                    'total_price' => $history->invoice ? (int)$history->invoice->total_price : null,
                    'doctor' => [
                        'id' =>   $history->doctor->id,
                        'name' =>   $history->doctor->name,
                    ],
                   
                ];
            })->all(),
        ];
        return $data;
    }
}
