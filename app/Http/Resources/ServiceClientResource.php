<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceClientResource extends JsonResource
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
            'image' => $this->image,
            'quantity_sold' => $this->quantity_sold,
            'min_price' => (int)$this->min_price,
            'unit' => $this->unit,  
            'category' =>[
                'id' => (int)$this->category_id,
                'name' => $this->category->name,
            ],
        ];
        if ($request->route()->getName() === 'service.detail') {
            $data = array_merge($data, [
                'max_price' => (int)$this->max_price,
                'description' => $this->description,
            ]);
        }

        return $data;
    }
    }

