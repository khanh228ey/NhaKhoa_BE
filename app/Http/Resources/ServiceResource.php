<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
                'name' => $this->name,
                'description' => $this->description,
                'image' => $this->image,
                'min_price' => $this->min_price,
                'max_price' => $this->max_price,
                'unit' => $this->unit,
                'quantity_sold' => $this->quantity_sold,
                'status' => $this->status,
                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ],
            ];
        }
    }
