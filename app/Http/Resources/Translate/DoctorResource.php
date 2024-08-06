<?php

namespace App\Http\Resources\Translate;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
                'name' => $this->translation->name ?? $this->name,
                'avatar' => $this->avatar,
                'phone_number' => $this->phone_number,
                'description' => $this->translation->description ?? $this->description,
        ];
        if ($request->route()->getName() === 'doctor.detail') {
            $data = array_merge($data, [
                'email' => $this->email,
                'gender' => $this->gender,
            ]);
        }

        return $data;
    }
}
