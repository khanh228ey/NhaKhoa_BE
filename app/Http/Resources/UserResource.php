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
        $data = [
            'id' => (int)$this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'email' => $this->email,
            'role' => [
                'id' => (int)$this->role_id,
                'name' => $this->role->name,
            ],
            'status' => (int)$this->status,
        ];
        if($request->route()->getName() === 'user.detail')  {
            $data = array_merge($data, [
                'gender' => (int)$this->gender,
                'address' => $this->address,
                'description' => $this->description,
            ]);
        }
    
        return $data;
    }
}

