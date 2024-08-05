<?php

namespace App\Http\Resources\Translate;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->translation->name,
            'image' => $this->image,
            'status' => (int)$this->status,
        ];
    
        if($request->route()->getName() === 'category.detail')  {
            $data = array_merge($data, [
                'description' => $this->translation->description,
                'services' => $this->services ? $this->services->map(function ($service) {
                    return [
                        'id' => (int)$service->id,
                        'name' => $service->name,
                        'image' => $service->image,
                        'unit' => $service->unit,
                        'min_price' => (int)$service->min_price,
                        'quantity_sold' => (int)$service->quantity_sold,
                    ];
                }) : null, 
            ]);
        }
    
        return $data;
    }
}
