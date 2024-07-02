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
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'phone_number' => $this->phone_number,
        //     'birthday' => $this->birthday,
        //     'email' => $this->email,
        //     'gender' => $this->gender,
        //     'address' => $this->address,
            // 'histories' => $this->histories->map(function ($history) {
            //     return [
            //         'id' => $history->id,
            //         'date' => $history->date,
            //         'time' => $history->time,
            //         'noted' => $history->noted,
            //     ];
            // }),
        // ];
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'email' => $this->email,
            'gender' => $this->gender,
            'address' => $this->address,
            'histories' => $this->Histories->map(function ($history) {
                return [
                    'id' => $history->id,
                    'date' => $history->date,
                    'time' => $history->time,
                ];
            })->all(),
        ];
        // if($request->route()->getName() === 'customer.detail')  {
        //     $data = array_merge($data, [
               
        //     ]);
        // }
    
        return $data;
    }
}
