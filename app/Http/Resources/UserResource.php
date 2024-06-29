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
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'role' => [
                'id' => $this->role_id,
                'name' => $this->role->name,
            ],
            'status' => $this->status,
        ];
        if($request->route()->getName() === 'user.detail')  {
            $data = array_merge($data, [
                'gender' => $this->gender,
                'address' => $this->address,
                'education' => $this->education,
                'certificate' => $this->certificate,
                'role_id' => $this->role->id,
            ]);
        }
    
        return $data;
    }
}

