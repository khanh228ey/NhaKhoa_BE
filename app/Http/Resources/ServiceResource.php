<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        // Dữ liệu cơ bản luôn được trả về
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'quantity_sold' => $this->quantity_sold,
            'status' => $this->status,
            'category' =>[
                'id' => $this->category_id,
                'name' => $this->category->name,
            ],
        ];
        if ($request->route()->getName() === 'service.detail') {
            $data = array_merge($data, [
                'description' => $this->description,
                'min_price' => $this->min_price,
                'max_price' => $this->max_price,
                'unit' => $this->unit,   
            ]);
        }

        return $data;
    }
}
