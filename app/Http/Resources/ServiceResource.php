<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'quantity_sold' => $this->quantity_sold,
            'min_price' => $this->min_price,
            'unit' => $this->unit, 
            'status' => $this->status,
            'category' =>[
                'id' => $this->category_id,
                'name' => $this->category->name,
            ],
        ];
        if ($request->route()->getName() === 'service.detail') {
            $data = array_merge($data, [
                'description' => $this->description,
                'max_price' => $this->max_price,
            ]);
        }

        return $data;
    }
}
