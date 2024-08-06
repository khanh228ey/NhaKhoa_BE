<?php

namespace App\Http\Resources\Translate;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResources extends JsonResource
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
            'image' => $this->image,
            'quantity_sold' => $this->quantity_sold,
            'min_price' => $this->min_price,
            'unit' => $this->translation->unit ?? $this->unit, 
            'status' => $this->status,
            'category' =>[
                'id' => $this->category_id,
                'name' => $this->category->translation->name,
            ],
        ];
        if ($request->route()->getName() === 'service.detail') {
            $data = array_merge($data, [
                'description' => $this->translation->description ?? $this->description,
                'max_price' => $this->max_price,
            ]);
        }

        return $data;
    }
}
