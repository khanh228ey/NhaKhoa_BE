<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'date' => $this->name,
            'birthday' => $this->birthday,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'education' => $this->education,
            'certificate' => $this->certificate,
            'role' => [
                'id' => $this->role->id,
                'name' => $this->role->name,
            ],
        ];
    }
}
